<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project; // Import the Project model
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Count all users
        $totalUsers = User::count();

        // Group users by department
        $usersByDepartment = User::select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        // Format department data
        $departments = $usersByDepartment->map(function ($item) {
            return [
                'name' => $item->department,
                'employeeCount' => $item->count,
            ];
        });

        // Get last 5 created projects
        $recentProjects = Project::latest()->take(5)->get();

        return response()->json([
            'totalUsers' => $totalUsers,
            'users' => $usersByDepartment,
            'departments' => $departments,
            'recentProjects' => $recentProjects, // Add this line
        ]);
    }
}
