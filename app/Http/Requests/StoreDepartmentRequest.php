<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\Services\DepartmentServiceInterface;
use App\DTOs\CreateDepartmentDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Department Request
 *
 * Validates department creation form data and converts to CreateDepartmentDTO.
 */
class StoreDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create departments');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments', 'code')],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
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
            'name.required' => 'Department name is required.',
            'name.unique' => 'This department name already exists.',
            'code.required' => 'Department code is required.',
            'code.unique' => 'This department code already exists.',
            'code.max' => 'Department code must not exceed 20 characters.',
        ];
    }

    /**
     * Convert validated data to CreateDepartmentDTO.
     */
    public function toDTO(): CreateDepartmentDTO
    {
        $validated = $this->validated();

        return new CreateDepartmentDTO(
            name: $validated['name'],
            code: strtoupper($validated['code']),
            description: $validated['description'] ?? null,
            isActive: $validated['is_active'] ?? true,
        );
    }
}
