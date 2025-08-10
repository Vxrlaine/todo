@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Stats cards -->
        <div class="bg-gray-800 rounded-lg p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400">Total Tasks</p>
                    <h3 class="text-2xl font-bold">{{ $totalTasks }}</h3>
                </div>
                <div class="p-3 rounded-full bg-red-500 bg-opacity-20 text-red-500">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-800 rounded-lg p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400">Completed</p>
                    <h3 class="text-2xl font-bold">{{ $completedTasks }}</h3>
                </div>
                <div class="p-3 rounded-full bg-green-500 bg-opacity-20 text-green-500">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-800 rounded-lg p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400">Pending</p>
                    <h3 class="text-2xl font-bold">{{ $pendingTasks }}</h3>
                </div>
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20 text-yellow-500">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Tasks -->
    <div class="bg-gray-800 rounded-lg p-6 shadow">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Recent Tasks</h2>
            <a href="{{ route('tasks.index') }}" class="text-red-500 hover:underline">View All</a>
        </div>
        
        @if($recentTasks->isEmpty())
            <p class="text-gray-400">No tasks yet. <a href="{{ route('tasks.create') }}" class="text-red-500 hover:underline">Create one now</a>.</p>
        @else
            <div class="space-y-4">
                @foreach($recentTasks as $task)
                    <div class="task-card rounded-lg p-4 flex items-center justify-between @if($task->completed) completed @endif">
                        <div>
                            <h3 class="font-medium @if($task->completed) line-through @endif">{{ $task->title }}</h3>
                            @if($task->due_date)
                                <p class="text-sm text-gray-400">
                                    Due: {{ $task->due_date->format('M d, Y') }}
                                    @if($task->due_date->isPast() && !$task->completed)
                                        <span class="text-red-500 ml-2">Overdue</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 rounded-full hover:bg-gray-700">
                                    <i class="fas fa-check @if($task->completed) text-green-500 @else text-gray-400 @endif"></i>
                                </button>
                            </form>
                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 rounded-full hover:bg-gray-700">
                                <i class="fas fa-edit text-blue-400"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection