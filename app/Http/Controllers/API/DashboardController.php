<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index()
    {
        // Total employees count
        $totalEmployees = Employee::count();

        // Employees count grouped by department
        $employeesByDepartment = Employee::select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        // Departments list with employee count (same as above)
        // You can also fetch departments from a departments table if you have one
        $departments = $employeesByDepartment->map(function ($item) {
            return [
                'name' => $item->department,
                'employeeCount' => $item->count,
            ];
        });

        return response()->json([
            'totalEmployees' => $totalEmployees,
            'employees' => $employeesByDepartment,
            'departments' => $departments,
        ]);
    }
}
