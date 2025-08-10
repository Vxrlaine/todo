<?php


// app/Http/Controllers/TaskController.php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use AuthorizesRequests;
   // app/Http/Controllers/TaskController.php
public function index()
{
    $query = Auth::user()->tasks()->latest();
    
    switch(request('filter')) {
        case 'completed':
            $query->where('completed', true);
            break;
        case 'pending':
            $query->where('completed', false);
            break;
        case 'overdue':
            $query->where('due_date', '<', now())->where('completed', false);
            break;
    }
    
    $tasks = $query->get();
    
    return view('tasks.index', compact('tasks'));
}

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'due_date' => 'nullable|date',
        ]);

        Auth::user()->tasks()->create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function toggleComplete(Task $task)
    {
        $this->authorize('update', $task);
        $task->update(['completed' => !$task->completed]);
        return back()->with('success', 'Task status updated.');
    }
}