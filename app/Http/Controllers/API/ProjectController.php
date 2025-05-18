<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::with('tasks')->get();
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required',
        'description' => 'nullable',
        'employee_ids' => 'array'
    ]);

    $project = Project::create([
        'name' => $data['name'],
        'description' => $data['description'] ?? null,
    ]);

    if (!empty($data['employee_ids'])) {
        $project->employees()->sync($data['employee_ids']);
    }

    return response()->json($project->load('employees', 'tasks'), 201);
}

    public function show($id)
    {
        return Project::with('tasks')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->only(['name', 'description']));

        if ($request->has('employee_ids')) {
            $project->employees()->sync($request->employee_ids);
        }

        return $project->load('employees', 'tasks');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->noContent();
    }
}
