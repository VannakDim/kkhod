@extends('layouts.app')

@section('title', 'Edit Course - ' . $course->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('courses.show', $course->slug) }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Course
            </a>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Course</h1>
        <p class="text-gray-600 mb-8">Update your course information</p>

        <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-lg shadow-md p-6">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Course Title</label>
                <input type="text" name="title" id="title" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('title', $course->title) }}">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="outline" class="block text-sm font-medium text-gray-700 mb-2">Course Outline</label>
                <textarea name="outline" id="outline" rows="6"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('outline', $course->outline) }}</textarea>
                <p class="text-gray-500 text-sm mt-1">Outline the main topics and learning objectives (one per line)</p>
                @error('outline')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                <input type="number" name="price" id="price" step="0.01" min="0" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('price', $course->price) }}">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Course Thumbnail</label>

                <!-- Current Thumbnail Preview -->
                @if ($course->thumbnail)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Current thumbnail:</p>
                        <img src="{{ $course->thumbnail_url }}" alt="Current thumbnail"
                            class="w-32 h-24 object-cover rounded-lg border">
                    </div>
                @endif

                <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-gray-500 text-sm mt-1">Upload a new thumbnail image (optional). Max size: 2MB</p>
                @error('thumbnail')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_published" value="1"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Publish this course (make it visible to students)</span>
                </label>
                @error('is_published')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('courses.show', $course->slug) }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-6 rounded">
                    Cancel
                </a>
                <div class="space-x-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-6 rounded">
                        Update Course
                    </button>
                    @can('delete', $course)
                        <button type="button" onclick="confirmDelete()"
                            class="bg-red-500 hover:bg-red-700 text-white py-2 px-6 rounded">
                            Delete Course
                        </button>
                    @endcan
                </div>
            </div>
        </form>

        <!-- Delete Confirmation Modal -->
        @can('delete', $course)
            <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Delete Course</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete "{{ $course->title }}"? This action cannot be undone and all
                                episodes will be permanently removed.
                            </p>
                        </div>
                        <div class="flex justify-center space-x-3 mt-4">
                            <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded">
                                Cancel
                            </button>
                            <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">
                                    Delete Course
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    <script>
        function confirmDelete() {
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('delete-modal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
