<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Pet>
     */
    protected $model = Pet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['cat', 'dog', 'bird', 'rabbit', 'hamster', 'fish'];
        $colors = ['#8B4513', '#FFA500', '#000000', '#FFFFFF', '#808080', '#FFB6C1', '#87CEEB', '#90EE90'];
        $accessories = ['hat', 'bow', 'collar', 'bandana', 'glasses', null];

        return [
            'name' => fake()->randomElement([
                'Fluffy', 'Buddy', 'Luna', 'Max', 'Bella', 'Charlie', 'Lucy', 'Cooper', 'Daisy', 'Rocky'
            ]),
            'type' => fake()->randomElement($types),
            'color' => fake()->randomElement($colors),
            'accessory' => fake()->randomElement($accessories),
            'attributes' => [
                'size' => fake()->randomElement(['small', 'medium', 'large']),
                'personality' => fake()->randomElement(['playful', 'calm', 'energetic', 'lazy', 'friendly']),
            ],
        ];
    }
}