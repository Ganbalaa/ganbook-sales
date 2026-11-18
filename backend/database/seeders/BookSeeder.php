<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

// ==========================================
// SEEDER KATALOG KOMIK (BOOK SEEDER)
// ==========================================
// Mengisi data awal katalog komik fisik (Manga, Manhwa, Manhua)
class BookSeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        $mangaCat = Category::firstOrCreate(['name' => 'Manga'], ['type' => 'origin', 'description' => 'Komik fisik asal Jepang']);
        $manhwaCat = Category::firstOrCreate(['name' => 'Manhwa'], ['type' => 'origin', 'description' => 'Komik fisik adaptasi webtoon Korea']);
        $manhuaCat = Category::firstOrCreate(['name' => 'Manhua'], ['type' => 'origin', 'description' => 'Komik fisik asal China']);

        $comics = [
            // ==========================================
            // KELOMPOK 1: MANGA (KOMIK JEPANG)
            // ==========================================
            [
                'category_id'    => $mangaCat->id,
                'title'          => 'One Piece Vol. 108',
                'author'         => 'Eiichiro Oda',
                'publisher'      => 'Elex Media Komputindo',
                'comic_type'     => 'Manga',
                'genre'          => 'Aksi, Petualangan, Shounen',
                'year'           => 2024,
                'price'          => 45000,
                'stock'          => 35,
                'cover_image'    => 'assets/covers/one-piece.svg',
                'description'    => 'Petualangan Monkey D. Luffy dan Bajak Laut Topi Jerami di Pulau Masa Depan Egghead dalam menguak rahasia Abad Kekosongan dan harta karun One Piece.',
                'is_popular'     => true,
                'is_recommended' => true,
            ],
            [
                'category_id'    => $mangaCat->id,
                'title'          => 'Naruto: Shippuden Vol. 72',
                'author'         => 'Masashi Kishimoto',
                'publisher'      => 'Elex Media Komputindo',
                'comic_type'     => 'Manga',
                'genre'          => 'Aksi, Ninja, Shounen',
                'year'           => 2023,
                'price'          => 40000,
                'stock'          => 25,
                'cover_image'    => 'assets/covers/naruto.svg',
                'description'    => 'Kisah puncak tekad ninja Naruto Uzumaki dan pertarungan legendaris penentuan masa depan dunia Shinobi di Lembah Akhir melawan Sasuke Uchiha.',
                'is_popular'     => true,
                'is_recommended' => false,
            ],
            [
                'category_id'    => $mangaCat->id,
                'title'          => 'Jujutsu Kaisen Vol. 25',
                'author'         => 'Gege Akutami',
                'publisher'      => 'M&C! Gramedia',
                'comic_type'     => 'Manga',
                'genre'          => 'Aksi, Supranatural, Dark Fantasy',
                'year'           => 2024,
                'price'          => 48000,
                'stock'          => 30,
                'cover_image'    => 'assets/covers/jujutsu-kaisen.svg',
                'description'    => 'Pertarungan sengit para penyihir jujutsu di Shinjuku Showdown melawan Raja Kutukan Ryomen Sukuna demi menyelamatkan dunia dari kehancuran.',
                'is_popular'     => true,
                'is_recommended' => true,
            ],
            [
                'category_id'    => $mangaCat->id,
                'title'          => 'Demon Slayer: Kimetsu no Yaiba Vol. 23',
                'author'         => 'Koyoharu Gotouge',
                'publisher'      => 'Elex Media Komputindo',
                'comic_type'     => 'Manga',
                'genre'          => 'Aksi, Historis, Supranatural',
                'year'           => 2023,
                'price'          => 45000,
                'stock'          => 20,
                'cover_image'    => 'assets/covers/demon-slayer.svg',
                'description'    => 'Tanjiro Kamado bersama Korps Pembasmi Iblis mempertaruhkan segalanya dalam pertempuran fajar terakhir melawan Muzan Kibutsuji.',
                'is_popular'     => false,
                'is_recommended' => true,
            ],
            [
                'category_id'    => $mangaCat->id,
                'title'          => 'Frieren: Beyond Journey\'s End Vol. 11',
                'author'         => 'Kanehito Yamada & Tsukasa Abe',
                'publisher'      => 'M&C! Gramedia',
                'comic_type'     => 'Manga',
                'genre'          => 'Fantasi, Petualangan, Drama',
                'year'           => 2024,
                'price'          => 50000,
                'stock'          => 18,
                'cover_image'    => 'assets/covers/frieren.svg',
                'description'    => 'Perjalanan elf penyihir Frieren menelusuri kembali jejak petualangannya bersama pahlawan Himmel dan mempelajari arti kehidupan manusia yang fana.',
                'is_popular'     => true,
                'is_recommended' => true,
            ],

            // ==========================================
            // KELOMPOK 2: MANHWA (KOMIK KOREA)
            // ==========================================
            [
                'category_id'    => $manhwaCat->id,
                'title'          => 'Solo Leveling Vol. 09',
                'author'         => 'Chugong & DUBU (REDICE)',
                'publisher'      => 'D&C Media / Phoenix',
                'comic_type'     => 'Manhwa',
                'genre'          => 'Aksi, Fantasi, Hunter System',
                'year'           => 2024,
                'price'          => 65000,
                'stock'          => 40,
                'cover_image'    => 'assets/covers/solo-leveling.svg',
                'description'    => 'Kisah kebangkitan Sung Jin-woo dari pemburu peringkat E terlemah di dunia menjadi Sang Raja Bayangan (Shadow Monarch) yang tak terkalahkan.',
                'is_popular'     => true,
                'is_recommended' => true,
            ],
            [
                'category_id'    => $manhwaCat->id,
                'title'          => 'Lookism Vol. 05',
                'author'         => 'Park Tae-jun',
                'publisher'      => 'Webtoon Graphic Books',
                'comic_type'     => 'Manhwa',
                'genre'          => 'Aksi, Kehidupan Sekolah, Drama',
                'year'           => 2024,
                'price'          => 55000,
                'stock'          => 22,
                'cover_image'    => 'assets/covers/lookism.svg',
                'description'    => 'Park Hyung-suk yang memiliki dua tubuh berbeda mengungkap sindikat misterius di balik empat regu besar Gangdong dan rahasia asal mula kekuatannya.',
                'is_popular'     => true,
                'is_recommended' => false,
            ],
            [
                'category_id'    => $manhwaCat->id,
                'title'          => 'Eleceed Vol. 03',
                'author'         => 'Jeho Son & ZHENA',
                'publisher'      => 'Webtoon Graphic Books',
                'comic_type'     => 'Manhwa',
                'genre'          => 'Aksi, Supranatural, Komedi',
                'year'           => 2023,
                'price'          => 55000,
                'stock'          => 15,
                'cover_image'    => 'assets/covers/eleceed.svg',
                'description'    => 'Jiwoo Seo, pemuda berkecepatan super, berlatih bersama Kayden, sang ranker terkuat di dunia yang terjebak di dalam tubuh kucing gendut lucu.',
                'is_popular'     => false,
                'is_recommended' => true,
            ],
            [
                'category_id'    => $manhwaCat->id,
                'title'          => 'The Greatest Estate Developer Vol. 02',
                'author'         => 'Lee Hyunmin & Kim BK',
                'publisher'      => 'Webtoon Graphic Books',
                'comic_type'     => 'Manhwa',
                'genre'          => 'Komedi, Fantasi, Isekai',
                'year'           => 2024,
                'price'          => 58000,
                'stock'          => 20,
                'cover_image'    => 'assets/covers/estate-developer.svg',
                'description'    => 'Seorang mahasiswa teknik sipil bereinkarnasi sebagai bangsawan malas Lloyd Frontera dan menyelamatkan tanahnya menggunakan teknologi konstruksi modern.',
                'is_popular'     => true,
                'is_recommended' => true,
            ],

            // ==========================================
            // KELOMPOK 3: MANHUA (KOMIK CHINA)
            // ==========================================
            [
                'category_id'    => $manhuaCat->id,
                'title'          => 'Tales of Demons and Gods Vol. 08',
                'author'         => 'Mad Snail',
                'publisher'      => 'Manhua Publishing House',
                'comic_type'     => 'Manhua',
                'genre'          => 'Kultivasi, Fantasi, Aksi',
                'year'           => 2023,
                'price'          => 52000,
                'stock'          => 18,
                'cover_image'    => 'assets/covers/tales-of-demons.svg',
                'description'    => 'Nie Li terlahir kembali ke masa mudanya membawa kenangan seumur hidup untuk melindungi Kota Cahaya Gemilang dari serbuan monster iblis.',
                'is_popular'     => true,
                'is_recommended' => false,
            ],
            [
                'category_id'    => $manhuaCat->id,
                'title'          => 'Apotheosis Vol. 04',
                'author'         => 'Ranzhi',
                'publisher'      => 'Kuaikan Comic Publishing',
                'comic_type'     => 'Manhua',
                'genre'          => 'Seni Bela Diri, Kultivasi, Aksi',
                'year'           => 2024,
                'price'          => 50000,
                'stock'          => 15,
                'cover_image'    => 'assets/covers/apotheosis.svg',
                'description'    => 'Luo Zheng yang jatuh menjadi budak menemukan kitab misterius yang memungkinkannya menempa tubuh fisiknya sendiri menjadi senjata ilahi tak tertandingi.',
                'is_popular'     => false,
                'is_recommended' => true,
            ],
            [
                'category_id'    => $manhuaCat->id,
                'title'          => 'Battle Through the Heavens Vol. 06',
                'author'         => 'Tian Can Tu Dou',
                'publisher'      => 'Manhua Publishing House',
                'comic_type'     => 'Manhua',
                'genre'          => 'Kultivasi, Aksi, Petualangan',
                'year'           => 2024,
                'price'          => 52000,
                'stock'          => 20,
                'cover_image'    => 'assets/covers/battle-through-heavens.svg',
                'description'    => 'Xiao Yan, jenius Dou Qi yang sempat kehilangan kekuatannya, menaklukkan api surgawi purba di bawah bimbingan roh guru Yao Chen.',
                'is_popular'     => true,
                'is_recommended' => true,
            ],
        ];

        foreach ($comics as $c) {
            Book::updateOrCreate(
                ['title' => $c['title']],
                $c
            );
        }
    }
}
