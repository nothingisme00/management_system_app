<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Login Request
 *
 * Validates login form data and converts to LoginDTO.
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Login is available to guests
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
        ];
    }

    /**
     * Convert validated data to LoginDTO.
     */
    public function toDTO(): LoginDTO
    {
        return LoginDTO::fromRequest($this->validated());
    }
}
