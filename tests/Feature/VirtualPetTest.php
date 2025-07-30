<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use App\Models\UserPet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VirtualPetTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_virtual_pet_platform(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('virtual-pet')
            ->has('availablePets')
            ->where('isAuthenticated', false)
            ->where('userPet', null)
        );
    }

    public function test_authenticated_user_can_see_adoption_interface(): void
    {
        $user = User::factory()->create();
        Pet::factory()->count(5)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('virtual-pet')
            ->where('isAuthenticated', true)
            ->where('userPet', null)
            ->has('availablePets', 5)
        );
    }

    public function test_user_can_adopt_a_pet(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create(['name' => 'Fluffy']);

        $response = $this->actingAs($user)->post('/adopt-pet', [
            'pet_id' => $pet->id,
            'custom_name' => 'My Fluffy',
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('user_pets', [
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'custom_name' => 'My Fluffy',
        ]);
    }

    public function test_user_must_be_authenticated_to_adopt_pet(): void
    {
        $pet = Pet::factory()->create();

        $response = $this->post('/adopt-pet', [
            'pet_id' => $pet->id,
            'custom_name' => 'Test Pet',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_adoption_requires_valid_pet_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/adopt-pet', [
            'pet_id' => 999,
            'custom_name' => 'Test Pet',
        ]);

        $response->assertSessionHasErrors(['pet_id']);
    }

    public function test_adoption_requires_custom_name(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();

        $response = $this->actingAs($user)->post('/adopt-pet', [
            'pet_id' => $pet->id,
            'custom_name' => '',
        ]);

        $response->assertSessionHasErrors(['custom_name']);
    }

    public function test_user_with_pet_sees_pet_care_interface(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();
        $userPet = UserPet::factory()->create([
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'custom_name' => 'My Pet',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('virtual-pet')
            ->where('isAuthenticated', true)
            ->where('userPet.id', $userPet->id)
            ->where('userPet.custom_name', 'My Pet')
            ->has('availablePets', 0)
        );
    }

    public function test_user_can_update_pet_customization(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();
        $userPet = UserPet::factory()->create([
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'custom_name' => 'Old Name',
            'custom_color' => '#FF0000',
        ]);

        $response = $this->actingAs($user)->patch("/user-pets/{$userPet->id}", [
            'custom_name' => 'New Name',
            'custom_color' => '#00FF00',
            'custom_accessory' => 'hat',
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('user_pets', [
            'id' => $userPet->id,
            'custom_name' => 'New Name',
            'custom_color' => '#00FF00',
            'custom_accessory' => 'hat',
        ]);
    }

    public function test_user_can_only_update_their_own_pet(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pet = Pet::factory()->create();
        $userPet = UserPet::factory()->create([
            'user_id' => $user2->id,
            'pet_id' => $pet->id,
        ]);

        $response = $this->actingAs($user1)->patch("/user-pets/{$userPet->id}", [
            'custom_name' => 'Hacked Name',
            'custom_color' => '#000000',
        ]);

        $response->assertStatus(403);
    }

    public function test_pet_update_validates_color_format(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create();
        $userPet = UserPet::factory()->create([
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);

        $response = $this->actingAs($user)->patch("/user-pets/{$userPet->id}", [
            'custom_name' => 'Valid Name',
            'custom_color' => 'invalid-color',
        ]);

        $response->assertSessionHasErrors(['custom_color']);
    }
}