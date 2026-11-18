<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

// ==========================================
// SEEDER KATEGORI & GENRE (CATEGORY SEEDER)
// ==========================================
// Menyiapkan kategori asal komik (Manga, Manhwa, Manhua) dan genre cerita
class CategorySeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     */
    public function run(): void
    {
        $categories = [
            // Asal Komik Utama (Origin)
            [
                'name'        => 'Manga',
                'type'        => 'origin',
                'description' => 'Komik fisik asal Jepang dengan ciri khas seni ekspresif dan alur cerita mendalam.',
            ],
            [
                'name'        => 'Manhwa',
                'type'        => 'origin',
                'description' => 'Komik fisik adaptasi webtoon Korea Selatan dengan gaya visual modern dan penuh warna.',
            ],
            [
                'name'        => 'Manhua',
                'type'        => 'origin',
                'description' => 'Komik fisik asal China dengan tema kultivasi, seni bela diri wuxia, dan fantasi historis.',
            ],

            // Genre Populer
            [
                'name'        => 'Aksi & Petualangan',
                'type'        => 'genre',
                'description' => 'Pertarungan seru, dinamika pertualangan menembus batas, dan perjalanan pahlawan.',
            ],
            [
                'name'        => 'Fantasi & Isekai',
                'type'        => 'genre',
                'description' => 'Dunia sihir, perpindahan dimensi lain, monster, dan sistem kenaikan level.',
            ],
            [
                'name'        => 'Supranatural & Misteri',
                'type'        => 'genre',
                'description' => 'Kutukan, iblis, pembasmi roh, dan teka-teki misteri yang menegangkan.',
            ],
            [
                'name'        => 'Komedi & Slice of Life',
                'type'        => 'genre',
                'description' => 'Kisah humor menghibur, kehidupan sehari-hari, arsitektur fantasi, dan kehangatan persahabatan.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat['name']],
                [
                    'type'        => $cat['type'],
                    'description' => $cat['description']
                ]
            );
        }
    }
}
