<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    //
    // List all employees
    public function index()
    {
        return response()->json(Employee::all());
    }

    // Store new employee
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $employee = Employee::create($request->all());
        return response()->json($employee, 201);
    }

    // Show single employee
    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    // Update employee
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees')->ignore($employee->id)],
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $employee->update($request->all());
        return response()->json($employee);
    }

    // Delete employee
    public function destroy($id)
    {
        Employee::destroy($id);
        return response()->json(null, 204);
    }
}
