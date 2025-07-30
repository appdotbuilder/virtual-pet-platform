<?php

namespace Database\Seeders;

use App\Models\Pet;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pets = [
            [
                'name' => 'Whiskers',
                'type' => 'cat',
                'color' => '#8B4513',
                'accessory' => 'bow',
                'attributes' => ['size' => 'medium', 'personality' => 'playful']
            ],
            [
                'name' => 'Buddy',
                'type' => 'dog',
                'color' => '#FFA500',
                'accessory' => 'collar',
                'attributes' => ['size' => 'large', 'personality' => 'friendly']
            ],
            [
                'name' => 'Chirpy',
                'type' => 'bird',
                'color' => '#87CEEB',
                'accessory' => null,
                'attributes' => ['size' => 'small', 'personality' => 'energetic']
            ],
            [
                'name' => 'Snowball',
                'type' => 'rabbit',
                'color' => '#FFFFFF',
                'accessory' => 'hat',
                'attributes' => ['size' => 'medium', 'personality' => 'calm']
            ],
            [
                'name' => 'Hammy',
                'type' => 'hamster',
                'color' => '#8B4513',
                'accessory' => null,
                'attributes' => ['size' => 'small', 'personality' => 'playful']
            ],
            [
                'name' => 'Goldie',
                'type' => 'fish',
                'color' => '#FFD700',
                'accessory' => null,
                'attributes' => ['size' => 'small', 'personality' => 'calm']
            ],
            [
                'name' => 'Shadow',
                'type' => 'cat',
                'color' => '#000000',
                'accessory' => 'collar',
                'attributes' => ['size' => 'medium', 'personality' => 'mysterious']
            ],
            [
                'name' => 'Luna',
                'type' => 'dog',
                'color' => '#C0C0C0',
                'accessory' => 'bandana',
                'attributes' => ['size' => 'medium', 'personality' => 'gentle']
            ]
        ];

        foreach ($pets as $petData) {
            Pet::create($petData);
        }
    }
}