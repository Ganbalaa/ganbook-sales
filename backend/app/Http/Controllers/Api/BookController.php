<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

// ==========================================
// BAGIAN: CONTROLLER MANAJEMEN KOMIK (BOOK CONTROLLER)
// Menangani katalog komik publik, filter Manga/Manhwa/Manhua, pencarian judul,
// serta operasi CRUD dan upload cover dari perangkat oleh Administrator.
// ==========================================
class BookController extends Controller
{
    // ==========================================
    // BAGIAN 1: MENAMPILKAN KATALOG KOMIK (INDEX)
    // Mendukung fitur pencarian judul/penulis, filter tipe komik, kategori, dan pengurutan.
    // ==========================================
    public function index(Request $request)
    {
        $query = Book::with('category');

        // 1. Filter pencarian teks bebas (Judul, Penulis, Penerbit, atau Genre)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        // 2. Filter tipe asal komik (Manga Jepang, Manhwa Korea, atau Manhua China)
        if ($request->filled('comic_type')) {
            $query->where('comic_type', $request->comic_type);
        }

        // 3. Filter berdasarkan ID kategori spesifik
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. Filter komik terpopuler untuk etalase beranda
        if ($request->boolean('is_popular')) {
            $query->where('is_popular', true);
        }

        // 5. Filter rekomendasi editor untuk etalase beranda
        if ($request->boolean('is_recommended')) {
            $query->where('is_recommended', true);
        }

        // 6. Pengurutan data (harga terendah, harga tertinggi, judul A-Z, atau terbaru)
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // 7. Pembatasan jumlah hasil yang dikembalikan (Limit)
        if ($request->filled('limit')) {
            $books = $query->take((int) $request->limit)->get();
        } else {
            $books = $query->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Katalog komik berhasil diambil.',
            'data'    => $books,
        ], 200);
    }

    // ==========================================
    // BAGIAN 2: MELIHAT DETAIL SATU KOMIK (SHOW)
    // Menampilkan sinopsis lengkap, spesifikasi, dan ketersediaan stok fisik komik.
    // ==========================================
    public function show($id)
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Komik tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail komik berhasil diambil.',
            'data'    => $book,
        ], 200);
    }

    // ==========================================
    // BAGIAN 3: MENAMBAH KOMIK BARU & UPLOAD COVER (STORE)
    // Menyimpan data komik baru dan mengunggah gambar cover ke Laravel Storage (Khusus Admin).
    // ==========================================
    public function store(Request $request)
    {
        // 1. Validasi data formulir dan berkas gambar cover (Maks. 2 MB: JPG, JPEG, PNG, WEBP)
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'title'          => 'required|string|max:200',
            'author'         => 'required|string|max:150',
            'publisher'      => 'required|string|max:150',
            'comic_type'     => 'required|in:Manga,Manhwa,Manhua',
            'genre'          => 'required|string|max:150',
            'year'           => 'required|integer|min:1950|max:' . (date('Y') + 1),
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'cover_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description'    => 'nullable|string',
            'is_popular'     => 'nullable',
            'is_recommended' => 'nullable',
        ], [
            'category_id.required' => 'Kategori komik wajib dipilih.',
            'title.required'       => 'Judul komik wajib diisi.',
            'author.required'      => 'Nama penulis / komikus wajib diisi.',
            'publisher.required'   => 'Nama penerbit wajib diisi.',
            'comic_type.required'  => 'Tipe komik (Manga/Manhwa/Manhua) wajib dipilih.',
            'price.required'       => 'Harga komik wajib diisi.',
            'stock.required'       => 'Stok komik wajib diisi.',
            'cover_image.image'    => 'File cover yang dipilih harus berupa file gambar.',
            'cover_image.mimes'    => 'Format file cover harus berupa JPG, JPEG, PNG, atau WEBP.',
            'cover_image.max'      => 'Ukuran file cover tidak boleh melebihi 2 MB (2048 KB).',
        ]);

        $comicData = [
            'category_id'    => $validated['category_id'],
            'title'          => $validated['title'],
            'author'         => $validated['author'],
            'publisher'      => $validated['publisher'],
            'comic_type'     => $validated['comic_type'],
            'genre'          => $validated['genre'],
            'year'           => (int) $validated['year'],
            'price'          => (float) $validated['price'],
            'stock'          => (int) $validated['stock'],
            'description'    => $request->description,
            'is_popular'     => $request->boolean('is_popular'),
            'is_recommended' => $request->boolean('is_recommended'),
        ];

        // 2. Pemrosesan penyimpanan file gambar cover ke folder storage/app/public/covers
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $extension = $file->getClientOriginalExtension();
            $filename = 'cover_' . time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('covers', $filename, 'public');
            $comicData['cover_image'] = 'storage/' . $path;
        } else {
            // Jika tidak ada cover diunggah, gunakan cover default bawaan
            $typeLower = strtolower($validated['comic_type']);
            $comicData['cover_image'] = "assets/covers/{$typeLower}-default.svg";
        }

        // 3. Simpan data komik ke database MySQL
        $book = Book::create($comicData);
        $book->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Komik baru berhasil ditambahkan beserta gambar cover.',
            'data'    => $book,
        ], 201);
    }

    // ==========================================
    // BAGIAN 4: MEMPERBARUI DATA KOMIK (UPDATE)
    // Memperbarui atribut komik dan menangani penggantian file cover secara bersih (Khusus Admin).
    // ==========================================
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Komik tidak ditemukan.',
            ], 404);
        }

        // 1. Validasi input data komik
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'title'          => 'required|string|max:200',
            'author'         => 'required|string|max:150',
            'publisher'      => 'required|string|max:150',
            'comic_type'     => 'required|in:Manga,Manhwa,Manhua',
            'genre'          => 'required|string|max:150',
            'year'           => 'required|integer|min:1950|max:' . (date('Y') + 1),
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'cover_image'    => 'nullable',
            'description'    => 'nullable|string',
            'is_popular'     => 'nullable',
            'is_recommended' => 'nullable',
        ], [
            'category_id.required' => 'Kategori komik wajib dipilih.',
            'title.required'       => 'Judul komik wajib diisi.',
            'author.required'      => 'Nama penulis / komikus wajib diisi.',
            'publisher.required'   => 'Nama penerbit wajib diisi.',
            'comic_type.required'  => 'Tipe komik (Manga/Manhwa/Manhua) wajib dipilih.',
            'price.required'       => 'Harga komik wajib diisi.',
            'stock.required'       => 'Stok komik wajib diisi.',
        ]);

        // 2. Validasi file jika Admin memilih file cover baru
        if ($request->hasFile('cover_image')) {
            $request->validate([
                'cover_image' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            ], [
                'cover_image.image' => 'File cover yang dipilih harus berupa file gambar.',
                'cover_image.mimes' => 'Format file cover harus berupa JPG, JPEG, PNG, atau WEBP.',
                'cover_image.max'   => 'Ukuran file cover tidak boleh melebihi 2 MB (2048 KB).',
            ]);
        }

        $comicData = [
            'category_id'    => $validated['category_id'],
            'title'          => $validated['title'],
            'author'         => $validated['author'],
            'publisher'      => $validated['publisher'],
            'comic_type'     => $validated['comic_type'],
            'genre'          => $validated['genre'],
            'year'           => (int) $validated['year'],
            'price'          => (float) $validated['price'],
            'stock'          => (int) $validated['stock'],
            'description'    => $request->description,
            'is_popular'     => $request->boolean('is_popular'),
            'is_recommended' => $request->boolean('is_recommended'),
        ];

        // 3. Jika terdapat berkas gambar cover baru yang diunggah
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $extension = $file->getClientOriginalExtension();
            $filename = 'cover_' . time() . '_' . uniqid() . '.' . $extension;
            $path = $file->storeAs('covers', $filename, 'public');

            $oldCover = $book->cover_image;

            // Perbarui path cover baru
            $comicData['cover_image'] = 'storage/' . $path;

            // Hapus file cover fisik lama dari storage jika bukan aset bawaan seeder
            if ($oldCover && !str_starts_with($oldCover, 'assets/')) {
                $oldStoragePath = str_replace('storage/', '', $oldCover);
                if (Storage::disk('public')->exists($oldStoragePath)) {
                    Storage::disk('public')->delete($oldStoragePath);
                }
            }
        }
        // Jika tidak ada cover baru yang dipilih, cover_image lama dipertahankan secara utuh

        $book->update($comicData);
        $book->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Data komik berhasil diperbarui.',
            'data'    => $book,
        ], 200);
    }

    // ==========================================
    // BAGIAN 5: MENGHAPUS KOMIK DARI KATALOG (DESTROY)
    // Menghapus data komik beserta berkas cover fisiknya dari media penyimpanan (Khusus Admin).
    // ==========================================
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Komik tidak ditemukan.',
            ], 404);
        }

        // Hapus file cover fisik dari storage jika ada (bukan aset bawaan)
        $oldCover = $book->cover_image;
        if ($oldCover && !str_starts_with($oldCover, 'assets/')) {
            $oldStoragePath = str_replace('storage/', '', $oldCover);
            if (Storage::disk('public')->exists($oldStoragePath)) {
                Storage::disk('public')->delete($oldStoragePath);
            }
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komik dan file cover berhasil dihapus dari katalog.',
        ], 200);
    }
}
