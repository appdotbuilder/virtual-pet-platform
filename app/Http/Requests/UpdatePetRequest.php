<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->route('userPet')->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'custom_name' => 'required|string|max:255|min:1',
            'custom_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'custom_accessory' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'custom_name.required' => 'Pet name is required.',
            'custom_name.min' => 'Pet name must be at least 1 character.',
            'custom_name.max' => 'Pet name cannot exceed 255 characters.',
            'custom_color.required' => 'Pet color is required.',
            'custom_color.regex' => 'Pet color must be a valid hex color (e.g., #FF0000).',
            'custom_accessory.max' => 'Accessory name cannot exceed 255 characters.',
        ];
    }
}