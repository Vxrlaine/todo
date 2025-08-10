<?php

namespace App\Http\Controllers\Api;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        try {
            $tasks = Auth::user()->tasks()->latest()->get();
            return response()->json([
                'success' => true,
                'data' => TaskResource::collection($tasks),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date'
            ]);

            $task = Auth::user()->tasks()->create($validated);

            return response()->json([
                'success' => true,
                'data' => new TaskResource($task),
                'message' => 'Task created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task creation failed',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function show(Task $task)
    {
        try {
            $this->authorize('view', $task);

            return response()->json([
                'success' => true,
                'data' => new TaskResource($task)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|nullable|string',
                'due_date' => 'sometimes|nullable|date',
                'completed' => 'sometimes|boolean'
            ]);

            $task->update($validated);

            return response()->json([
                'success' => true,
                'data' => new TaskResource($task),
                'message' => 'Task updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task update failed',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Task $task)
    {
        try {
            $this->authorize('update', $task);

            $task->update(['completed' => !$task->completed]);

            return response()->json([
                'success' => true,
                'data' => new TaskResource($task),
                'message' => 'Task status updated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle task status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
// Compare this snippet from app/Http/Controllers/Api/AuthController.php:
//     ]);
//     }