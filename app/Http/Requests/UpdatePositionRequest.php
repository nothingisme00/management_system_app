<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\UpdatePositionDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Position Request
 *
 * Validates position update form data and converts to UpdatePositionDTO.
 */
class UpdatePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update positions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $positionId = $this->route('position');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('positions', 'name')->ignore($positionId)],
            'code' => ['required', 'string', 'max:20', Rule::unique('positions', 'code')->ignore($positionId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'level' => ['required', 'integer', 'min:1', 'max:5'],
            'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
            'is_active' => ['required', 'boolean'],
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
            'name.required' => 'Position name is required.',
            'name.unique' => 'This position name already exists.',
            'code.required' => 'Position code is required.',
            'code.unique' => 'This position code already exists.',
            'code.max' => 'Position code must not exceed 20 characters.',
            'level.required' => 'Position level is required.',
            'level.min' => 'Position level must be at least 1.',
            'level.max' => 'Position level must not exceed 5.',
            'department_id.exists' => 'The selected department does not exist.',
            'is_active.required' => 'Active status is required.',
        ];
    }

    /**
     * Convert validated data to UpdatePositionDTO.
     */
    public function toDTO(): UpdatePositionDTO
    {
        $validated = $this->validated();

        return new UpdatePositionDTO(
            name: $validated['name'],
            code: strtoupper($validated['code']),
            description: $validated['description'] ?? null,
            level: $validated['level'],
            departmentId: $validated['department_id'] ?? null,
            isActive: $validated['is_active'],
        );
    }
}
