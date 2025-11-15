@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Create New Course</h1>

    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Course Title</label>
            <input type="text" name="title" id="title" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ old('title') }}">
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="outline" class="block text-sm font-medium text-gray-700 mb-2">Course Outline</label>
            <textarea name="outline" id="outline" rows="6"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('outline') }}</textarea>
            <p class="text-gray-500 text-sm mt-1">Outline the main topics and learning objectives</p>
        </div>

        <div class="mb-6">
            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
            <input type="number" name="price" id="price" step="0.01" min="0" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ old('price', 0) }}">
            @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Course Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('thumbnail')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('courses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-6 rounded">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-6 rounded">
                Create Course
            </button>
        </div>
    </form>
</div>
@endsection