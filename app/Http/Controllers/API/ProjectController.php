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
        'description' => 'nullable'
    ]);

    return Project::create($data);
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
