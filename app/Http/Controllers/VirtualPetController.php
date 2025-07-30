<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdoptPetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use App\Models\UserPet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VirtualPetController extends Controller
{
    /**
     * Display the main virtual pet page.
     */
    public function index()
    {
        $user = Auth::user();
        $userPet = null;
        $availablePets = [];

        if ($user) {
            // Get user's current pet
            $userPet = UserPet::with('pet')
                ->where('user_id', $user->id)
                ->latest('adopted_at')
                ->first();
        }

        // If no user or no pet, show available pets for adoption
        if (!$user || !$userPet) {
            $availablePets = Pet::all();
        }

        return Inertia::render('virtual-pet', [
            'userPet' => $userPet,
            'availablePets' => $availablePets,
            'isAuthenticated' => (bool) $user,
        ]);
    }

    /**
     * Adopt a pet for the authenticated user.
     */
    public function store(AdoptPetRequest $request)
    {

        $user = Auth::user();
        $pet = Pet::findOrFail($request->validated()['pet_id']);

        // Create user pet adoption
        $userPet = UserPet::create([
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'custom_name' => $request->validated()['custom_name'],
            'custom_color' => $pet->color,
            'custom_accessory' => $pet->accessory,
            'happiness' => 100,
            'hunger' => 0,
            'adopted_at' => now(),
        ]);

        $userPet->load('pet');

        return Inertia::render('virtual-pet', [
            'userPet' => $userPet,
            'availablePets' => [],
            'isAuthenticated' => true,
        ]);
    }

    /**
     * Update pet customization.
     */
    public function update(UpdatePetRequest $request, UserPet $userPet)
    {
        $userPet->update($request->validated());

        $userPet->load('pet');

        return Inertia::render('virtual-pet', [
            'userPet' => $userPet,
            'availablePets' => [],
            'isAuthenticated' => true,
        ]);
    }
}