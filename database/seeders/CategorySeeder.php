<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Musik & Konser',
                'description' => 'Pertunjukan musik live, konser band, dan festival musik.',
            ],
            [
                'name'        => 'Seminar & Workshop',
                'description' => 'Kegiatan edukasi, pelatihan, dan pengembangan diri.',
            ],
            [
                'name'        => 'Olahraga',
                'description' => 'Kompetisi olahraga, fun run, turnamen, dan kegiatan fisik lainnya.',
            ],
            [
                'name'        => 'Seni & Budaya',
                'description' => 'Pameran seni, pertunjukan teater, dan festival budaya.',
            ],
            [
                'name'        => 'Teknologi',
                'description' => 'Konferensi teknologi, hackathon, dan pameran inovasi.',
            ],
            [
                'name'        => 'Kuliner & Lifestyle',
                'description' => 'Festival makanan, bazaar, dan pameran gaya hidup.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
