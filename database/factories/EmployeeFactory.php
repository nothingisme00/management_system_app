<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a random department to ensure employee_id generation works
        $department = \App\Models\Department::inRandomOrder()->first()
            ?? \App\Models\Department::factory()->create();

        $departmentCode = $department->code;
        $sequence = fake()->unique()->numberBetween(1, 9999);

        return [
            'user_id' => \App\Models\User::factory(),
            'employee_id' => sprintf('EMP-%s-%04d', strtoupper($departmentCode), $sequence),
            'department_id' => $department->id,
            'position_id' => null, // Will be set by seeder or test
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'join_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'termination_date' => null,
            'employment_status' => 'active',
        ];
    }

    /**
     * Indicate that the employee is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the employee is terminated.
     */
    public function terminated(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_status' => 'terminated',
            'termination_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }
}
