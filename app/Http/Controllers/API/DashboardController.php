<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Count only users with role 'employee'
        $totalEmployees = User::where('role', 'employee')->count();

        // Group employees by department
        $employeesByDepartment = User::where('role', 'employee')
            ->select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        // Format department data
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
