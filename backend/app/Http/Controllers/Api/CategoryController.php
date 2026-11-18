<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

// ==========================================
// BAGIAN: CONTROLLER KATEGORI KOMIK (CATEGORY CONTROLLER)
// Menangani operasi CRUD (Create, Read, Update, Delete) kategori dan genre komik.
// ==========================================
class CategoryController extends Controller
{
    // ==========================================
    // BAGIAN 1: MENAMPILKAN DAFTAR KATEGORI (INDEX)
    // Mengambil seluruh kategori beserta kalkulasi total judul buku di masing-masing kategori.
    // ==========================================
    public function index()
    {
        $categories = Category::withCount('books')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil.',
            'data'    => $categories,
        ], 200);
    }

    // ==========================================
    // BAGIAN 2: MENAMBAHKAN KATEGORI BARU (STORE)
    // Memvalidasi nama kategori agar unik dan menyimpannya ke database (Khusus Admin).
    // ==========================================
    public function store(Request $request)
    {
        // Validasi input nama dan deskripsi
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah terdaftar.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori buku berhasil ditambahkan.',
            'data'    => $category,
        ], 201);
    }

    // ==========================================
    // BAGIAN 3: MELIHAT DETAIL KATEGORI (SHOW)
    // Menampilkan detail satu kategori komik berdasarkan ID.
    // ==========================================
    public function show($id)
    {
        $category = Category::withCount('books')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail kategori berhasil diambil.',
            'data'    => $category,
        ], 200);
    }

    // ==========================================
    // BAGIAN 4: MEMPERBARUI DATA KATEGORI (UPDATE)
    // Mengubah nama atau deskripsi kategori yang sudah ada (Khusus Admin).
    // ==========================================
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah digunakan oleh kategori lain.',
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data'    => $category,
        ], 200);
    }

    // ==========================================
    // BAGIAN 5: MENGHAPUS KATEGORI (DESTROY)
    // Menghapus kategori dari sistem jika sudah tidak memiliki komik yang terkait (Khusus Admin).
    // ==========================================
    public function destroy($id)
    {
        $category = Category::withCount('books')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        // Cegah penghapusan jika masih terdapat buku dalam kategori ini untuk menjaga integritas data
        if ($category->books_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih memuat ' . $category->books_count . ' buku.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ], 200);
    }
}
