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
        'status' => 'nullable|string',
        'employee_ids' => 'array'
    ]);

    $task = Task::create([
        'project_id' => $data['project_id'],
        'title' => $data['title'],
        'status' => $data['status'] ?? 'todo'
    ]);

    if (!empty($data['employee_ids'])) {
        $task->employees()->sync($data['employee_ids']);
    }

    return $task->load('employees');
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
