<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    
    public function definition(): array
    {
        return [
            // Generates 'EMP-' followed by 5 random digits (e.g., EMP-48291)
            'employee_id' => 'EMP-' . fake()->unique()->numerify('#####'),
            
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'department' => fake()->randomElement([
                'Human Resources',
                'Information Technology',
                'Accounting',
                'Payroll',
                'Operations',
                'Administration',
                'Marketing'
            ]),
        ];
    }
}