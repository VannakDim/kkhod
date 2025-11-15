@extends('layouts.app')

@section('title', 'All Courses')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">All Courses</h1>
        <p class="text-gray-600 mt-2">Expand your knowledge with our curated courses</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($course->description, 100) }}</p>

                    <div class="flex items-center justify-between mb-4">
                        <span class="text-blue-600 font-bold">${{ number_format($course->price, 2) }}</span>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-play-circle mr-1"></i>
                            <span>{{ $course->episodes_count }} episodes</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">By {{ $course->user->name }}</span>
                        <a href="{{ route('courses.show', $course) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm">
                            View Course
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($courses->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600">No courses available yet</h3>
            <p class="text-gray-500 mt-2">Check back later for new courses</p>
        </div>
    @endif
@endsection
