<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // ✅ Désactiver les FK pour pouvoir truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Product::truncate();
        \App\Models\Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            ['name' => 'Quantum Tech', 'slug' => 'quantum'],
            ['name' => 'AI Wearables', 'slug' => 'wearables'],
            ['name' => 'Cyber Home', 'slug' => 'home'],
            ['name' => 'VR Gaming', 'slug' => 'gaming'],
        ];

        $unsplashKeys = [
            'quantum'   => 'quantum-computing,processor',
            'wearables' => 'smartwatch,ar-glasses,tech-fashion',
            'home'      => 'smart-home,server-rack,neon-room',
            'gaming'    => 'gaming-setup,vr-headset,cyberpunk',
        ];

        foreach ($categories as $catData) {
            $category = \App\Models\Category::create([
                'name' => $catData['name'],
                'slug' => $catData['slug'],
            ]);

            $techSuffixes = ['X-1', 'Pro Neon', 'Core', 'Ghost', 'Nova'];

            for ($i = 0; $i < 5; $i++) {
                $name = $catData['name'] . " " . $techSuffixes[$i];
                $key  = $unsplashKeys[$catData['slug']];

                \App\Models\Product::create([
                    'name'        => $name,
                    'slug'        => \Illuminate\Support\Str::slug($name),
                    'description' => "Le futur de la technologie {$catData['name']} est arrivé chez DirToi.",
                    'price'       => rand(299, 4999),
                    'stock'       => rand(1, 20),
                    'category_id' => $category->id,
                    'image'       => "https://picsum.photos/seed/{$catData['slug']}{$i}/600/400",
                ]);
            }
        }
    }
}