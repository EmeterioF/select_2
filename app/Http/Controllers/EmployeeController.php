<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // This method returns the employees index page (view file).
    // It is usually used to display the Select2 dropdown page.
    public function index(){

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

        // Get the "page" value sent from AJAX.
        // If no page is provided, default to page 1.
        // This is used for pagination.
        $page = $request->page ?? 1;

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
                ->orWhere('last_name', 'like', "%{$search}%");
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
            
            // Format data in the structure that Select2 requires.
            // Select2 requires:
            // - id
            // - text
            $results[] = [
                'id' => $employee->id,

                // Combine employee ID + full name
                // This is what will appear in the dropdown.
                'text' => $employee->employee_id . ' - ' . 
                          $employee->first_name . ' ' . 
                          $employee->last_name
            ];
        }

        // Return JSON response to the frontend (Select2).
        // Select2 reads this data and displays it in the dropdown.
        return response()->json([
            
            // The list of employees to display.
            'results' => $results,

            // Pagination information.
            // hasMorePages() checks if there are more records
            // beyond the current page.
            'pagination' => [
                'more' => $employees->hasMorePages()
            ]
        ]);
    }
}