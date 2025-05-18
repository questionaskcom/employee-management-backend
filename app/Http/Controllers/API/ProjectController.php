<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    //

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

    $project = Project::create($data);
    if (!empty($data['employee_ids'])) {
        $project->employees()->sync($data['employee_ids']);
    }

    return $project->load('employees', 'tasks');
}


public function show($id)
{
    return Project::with('tasks')->findOrFail($id);
}

public function update(Request $request, $id)
{
    $project = Project::findOrFail($id);
    $project->update($request->all());
    return $project;
}

public function destroy($id)
{
    $project = Project::findOrFail($id);
    $project->delete();
    return response()->noContent();
}

}
