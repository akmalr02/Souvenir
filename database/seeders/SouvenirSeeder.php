<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Souvenir;

class SouvenirSeeder extends Seeder
{
    public function run()
    {
        $souvenirs = [
            [
                'name' => 'Miniature Eiffel Tower',
                'description' => 'A beautiful miniature of the Eiffel Tower, perfect for decoration.',
                'price' => 15.99,
                'quantity' => 50
            ],
            [
                'name' => 'Keychain Set',
                'description' => 'A set of 5 assorted keychains representing different landmarks.',
                'price' => 9.99,
                'quantity' => 200
            ],
            [
                'name' => 'Souvenir T-shirt',
                'description' => 'Comfortable cotton T-shirt with a print of a famous city skyline.',
                'price' => 12.99,
                'quantity' => 100
            ],
            [
                'name' => 'Ceramic Mug',
                'description' => 'A ceramic mug with a beautiful print of a historic site.',
                'price' => 8.50,
                'quantity' => 150
            ],
            [
                'name' => 'Fridge Magnet',
                'description' => 'A colorful fridge magnet depicting a popular tourist spot.',
                'price' => 5.00,
                'quantity' => 300
            ],
        ];

        foreach ($souvenirs as $souvenir) {
            Souvenir::create($souvenir);
        }
    }
}
