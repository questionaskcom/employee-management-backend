<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // ✅ Get all tasks with users and project
    public function index()
    {
        return Task::with('users', 'project')->get();
    }

    // ✅ Get a specific task with users and project
    public function show($id)
    {
        return Task::with('users', 'project')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string',
            'status' => 'in:pending,done',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task = Task::create([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'status' => $request->status ?? 'pending',
        ]);

        $task->users()->sync($request->user_ids);

        return response()->json($task->load('users'), 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());

        if ($request->has('user_ids')) {
            $task->users()->sync($request->user_ids);
        }

        return $task->load('users');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->noContent();
    }
}
