<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use App\Models\UserPet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPet>
 */
class UserPetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\UserPet>
     */
    protected $model = UserPet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ['#8B4513', '#FFA500', '#000000', '#FFFFFF', '#808080', '#FFB6C1', '#87CEEB', '#90EE90'];
        $accessories = ['hat', 'bow', 'collar', 'bandana', 'glasses', null];

        return [
            'user_id' => User::factory(),
            'pet_id' => Pet::factory(),
            'custom_name' => fake()->randomElement([
                'Fluffy', 'Buddy', 'Luna', 'Max', 'Bella', 'Charlie', 'Lucy', 'Cooper', 'Daisy', 'Rocky'
            ]),
            'custom_color' => fake()->randomElement($colors),
            'custom_accessory' => fake()->randomElement($accessories),
            'happiness' => fake()->numberBetween(50, 100),
            'hunger' => fake()->numberBetween(0, 50),
            'last_fed_at' => fake()->optional()->dateTimeBetween('-1 day', 'now'),
            'adopted_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}