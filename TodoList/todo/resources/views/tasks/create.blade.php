@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="container mx-auto max-w-2xl">
    <div class="bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold">Create New Task</h2>
        </div>
        
        <form action="{{ route('tasks.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium mb-2">Title</label>
                <input type="text" name="title" id="title" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none"></textarea>
            </div>
            
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium mb-2">Due Date</label>
                <input type="date" name="due_date" id="due_date" class="px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none">
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-gray-600 rounded-lg hover:bg-gray-700 transition">Cancel</a>
                <button type="submit" class="btn-primary px-4 py-2 rounded-lg">Create Task</button>
            </div>
        </form>
    </div>
</div>
@endsection