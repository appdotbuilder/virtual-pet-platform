<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdoptPetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pet_id' => 'required|exists:pets,id',
            'custom_name' => 'required|string|max:255|min:1',
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
            'pet_id.required' => 'Please select a pet to adopt.',
            'pet_id.exists' => 'The selected pet is not available.',
            'custom_name.required' => 'Please give your pet a name.',
            'custom_name.min' => 'Pet name must be at least 1 character.',
            'custom_name.max' => 'Pet name cannot exceed 255 characters.',
        ];
    }
}