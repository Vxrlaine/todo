@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">My Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="btn-primary px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Task
        </a>
    </div>
    
    <!-- Task Filters -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('tasks.index') }}" class="px-4 py-2 rounded-lg @if(request('filter') === null) bg-red-500 @else bg-gray-700 @endif">
            All
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'pending']) }}" class="px-4 py-2 rounded-lg @if(request('filter') === 'pending') bg-red-500 @else bg-gray-700 @endif">
            Pending
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="px-4 py-2 rounded-lg @if(request('filter') === 'completed') bg-red-500 @else bg-gray-700 @endif">
            Completed
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'overdue']) }}" class="px-4 py-2 rounded-lg @if(request('filter') === 'overdue') bg-red-500 @else bg-gray-700 @endif">
            Overdue
        </a>
    </div>
    
    @if($tasks->isEmpty())
        <div class="bg-gray-800 rounded-lg p-8 text-center">
            <i class="fas fa-tasks text-4xl mb-4 text-gray-400"></i>
            <h3 class="text-xl mb-2">No tasks found</h3>
            <p class="text-gray-400 mb-4">Get started by creating a new task</p>
            <a href="{{ route('tasks.create') }}" class="btn-primary px-4 py-2 rounded-lg inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Create Task
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tasks as $task)
                <div class="task-card rounded-lg p-6 @if($task->completed) completed @endif">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-lg @if($task->completed) line-through @endif">{{ $task->title }}</h3>
                            @if($task->due_date)
                                <p class="text-sm mt-1 @if($task->due_date->isPast() && !$task->completed) text-red-400 @else text-gray-400 @endif">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $task->due_date->format('M d, Y') }}
                                    @if($task->due_date->isPast() && !$task->completed)
                                        <span class="ml-2">(Overdue)</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                        <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-1 rounded-full hover:bg-gray-700">
                                @if($task->completed)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @else
                                    <i class="far fa-circle text-gray-400"></i>
                                @endif
                            </button>
                        </form>
                    </div>
                    
                    @if($task->description)
                        <p class="text-gray-300 mb-4 text-sm">{{ $task->description }}</p>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">
                            Created: {{ $task->created_at->diffForHumans() }}
                        </span>
                        <div class="flex space-x-2">
                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 rounded-full hover:bg-gray-700">
                                <i class="fas fa-edit text-blue-400"></i>
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-full hover:bg-gray-700">
                                    <i class="fas fa-trash text-red-400"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection