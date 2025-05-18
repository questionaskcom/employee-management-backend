<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    //

     public function index()
    {
        return response()->json(Department::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string|max:255',
        ]);

        $data = $request->only(['department_name']);
       
        try {
            $Department = Department::create($data);
            return response()->json($Department, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    // Show single user
    public function show($id)
    {
        $Department = Department::findOrFail($id);
        return response()->json($Department);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $Department = Department::findOrFail($id);

        $request->validate([
            'department_name' => 'required|string|max:255',

        ]);

        $data = $request->all();

      

        $Department->update($data);

        return response()->json($Department);
    }

    // Delete user
    public function destroy($id)
    {
        $Department = Department::findOrFail($id);
        $Department->delete();

        return response()->json(null, 204);
    }
}
