<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    // List all employees (filtered by role if needed)
    public function index()
    {
        // Optionally filter by role:
        return response()->json(User::where('role', 'employee')->get());
    }

    // Store new employee
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->all();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Set a default password or leave null depending on your policy
            $data['password'] = Hash::make('defaultpassword'); // or skip this
        }

        $data['role'] = 'employee'; // Ensure this user is marked as an employee

        $employee = User::create($data);

        return response()->json($employee, 201);
    }

    // Show single employee
    public function show($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        return response()->json($employee);
    }

    // Update employee
    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($employee->id)],
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->all();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Don't update if password not provided
        }

        $employee->update($data);

        return response()->json($employee);
    }

    // Delete employee
    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $employee->delete();

        return response()->json(null, 204);
    }
}
