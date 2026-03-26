<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // This method returns the employees index page (view file).
    // It is usually used to display the Select2 dropdown page.
    public function index()
    {
        // Returns the Blade view located at:
        // resources/views/employees/index.blade.php
        return view('employees.index');
    }

    // This method handles the AJAX search request from Select2.
    // It receives data sent from the frontend (search term + page number)
    public function search(Request $request)
    {
        // Get the "search" value sent from AJAX.
        // This is what the user types in the Select2 dropdown.
        $search = $request->search;

        // Start building the database query.
        // We use a closure (function inside where) to group conditions.
        $employees = Employee::where(function ($query) use ($search) {
            // Check if search keyword exists.
            if ($search) {
                // Search employee_id column using partial matching.
                // Example: if user types "123",
                // it will match any employee_id containing "123".
                $query->where('employee_id', 'like', "%{$search}%")
                    // OR search in first_name column.
                    ->orWhere('first_name', 'like', "%{$search}%")
                    // OR search in last_name column.
                    ->orWhere('last_name', 'like', "%{$search}%")
                    // OR search in department column.
                    ->orWhere('department', 'like', "%{$search}%");
            }
        })
            // Paginate the results.
            // This limits results to 20 per page.
            // This is important for performance and large datasets.
            ->paginate(20);

        // Create an empty array.
        // This will store formatted data for Select2.
        $results = [];

        // Loop through each employee returned from the database.
        foreach ($employees as $employee) {
            $fullName = $employee->first_name . ' ' . $employee->last_name;

            // Format data in the structure that Select2 requires.
            $results[] = [
                'id' => $employee->id,
                // Include department in dropdown label.
                'text' => $employee->employee_id . ' - ' . $fullName . ' [ ' . $employee->department . ' ]',
                // Extra metadata used by the frontend to auto-fill readonly fields.
                'employee_id' => $employee->employee_id,
                'name' => $fullName,
                'department' => $employee->department,
                'email' => $employee->email,
                'phone_number' => $employee->phone_number,
                'profile_photo' => $this->getProfilePhoto($employee->employee_id),
            ];
        }

        // Return JSON response to the frontend (Select2).
        return response()->json([
            // The list of employees to display.
            'results' => $results,

            // Pagination information.
            'pagination' => [
                'more' => $employees->hasMorePages(),
            ],
        ]);
    }

    private function getProfilePhoto(string $employeeId): ?string
    {
        $photos = [
            'EMP-00001' => asset('images/profiles/emp-00001.jpg'),
            'EMP-00002' => asset('images/profiles/emp-00002.jpeg'),
            'EMP-00003' => asset('images/profiles/emp-00003.jpeg'),
            'EMP-00004' => asset('images/profiles/emp-00004.jpeg'),
            'EMP-00005' => asset('images/profiles/emp-00005.jpeg'),
        ];

        return $photos[$employeeId] ?? null;
    }
}
