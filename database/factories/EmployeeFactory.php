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
        $year = now()->year;
        $sequence = fake()->unique()->numberBetween(1, 9999);

        return [
            'user_id' => \App\Models\User::factory(),
            'employee_id' => sprintf('EMP-%d-%04d', $year, $sequence),
            'department_id' => null, // Will be set by seeder or test
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
