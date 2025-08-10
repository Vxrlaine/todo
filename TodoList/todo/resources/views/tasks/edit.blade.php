@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<div class="container mx-auto max-w-2xl">
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold">Edit Task</h2>
        </div>
        
        <form action="{{ route('tasks.update', $task) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium mb-2">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none">{{ old('description', $task->description) }}</textarea>
            </div>
            
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium mb-2">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none">
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-gray-600 rounded-lg hover:bg-gray-700 transition">Cancel</a>
                <button type="submit" class="btn-primary px-4 py-2 rounded-lg">Update Task</button>
            </div>
        </form>
    </div>
</div>
@endsection