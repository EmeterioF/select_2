<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $presetEmployees = [
            [
                'employee_id' => 'EMP-00001',
                'first_name' => 'Stefan',
                'last_name' => 'Boyer',
                'email' => 'isabell11@example.net',
                'phone_number' => '+1-205-425-2595',
                'department' => 'Accounting',
            ],
            [
                'employee_id' => 'EMP-00002',
                'first_name' => 'Ava',
                'last_name' => 'Martinez',
                'email' => 'ava.martinez@example.com',
                'phone_number' => '+1-617-555-0124',
                'department' => 'Human Resources',
            ],
            [
                'employee_id' => 'EMP-00003',
                'first_name' => 'Liam',
                'last_name' => 'Walker',
                'email' => 'liam.walker@example.com',
                'phone_number' => '+1-415-555-0189',
                'department' => 'Information Technology',
            ],
            [
                'employee_id' => 'EMP-00004',
                'first_name' => 'Mia',
                'last_name' => 'Reyes',
                'email' => 'mia.reyes@example.com',
                'phone_number' => '+1-303-555-0177',
                'department' => 'Marketing',
            ],
            [
                'employee_id' => 'EMP-00005',
                'first_name' => 'Noah',
                'last_name' => 'Santos',
                'email' => 'noah.santos@example.com',
                'phone_number' => '+1-702-555-0116',
                'department' => 'Operations',
            ],
        ];

        foreach ($presetEmployees as $employee) {
            Employee::updateOrCreate(
                ['employee_id' => $employee['employee_id']],
                $employee
            );
        }

        Employee::factory()->count(45)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
