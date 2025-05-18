<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
class TaskController extends Controller
{
    //

    public function store(Request $request)
{
    $data = $request->validate([
        'project_id' => 'required|exists:projects,id',
        'title' => 'required',
    ]);

    return Task::create($data);
}

public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);
    $task->update($request->all());
    return $task;
}

public function destroy($id)
{
    $task = Task::findOrFail($id);
    $task->delete();
    return response()->noContent();
}

}
