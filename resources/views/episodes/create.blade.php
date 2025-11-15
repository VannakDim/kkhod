@extends('layouts.app')

@section('title', 'Add Episode - ' . $course->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Course
        </a>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-2">Add New Episode</h1>
    <p class="text-gray-600 mb-8">for "{{ $course->title }}"</p>

    <form action="{{ route('courses.episodes.store', $course) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Episode Title</label>
            <input type="text" name="title" id="title" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ old('title') }}">
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Episode Order</label>
                <input type="number" name="order" id="order" required min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{ old('order', $course->episodes->count() + 1) }}">
                @error('order')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                <input type="number" name="duration" id="duration" required min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{ old('duration') }}">
                @error('duration')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="video" class="block text-sm font-medium text-gray-700 mb-2">Video File</label>
            <input type="file" name="video" id="video" accept="video/*" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-gray-500 text-sm mt-1">Supported formats: MP4, MOV, AVI. Max size: 500MB</p>
            @error('video')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="file_attachments" class="block text-sm font-medium text-gray-700 mb-2">File Attachments (Optional)</label>
            <input type="file" name="file_attachments" id="file_attachments"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-gray-500 text-sm mt-1">Code files, PDFs, etc. Max size: 10MB</p>
            @error('file_attachments')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_preview" value="1" 
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                       {{ old('is_preview') ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Make this episode available as preview</span>
            </label>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('courses.show', $course) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-6 rounded">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-6 rounded">
                Add Episode
            </button>
        </div>
    </form>
</div>
@endsection