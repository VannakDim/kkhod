@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">My Courses</h1>

    @if($enrollments->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600">You're not enrolled in any courses yet</h3>
        <p class="text-gray-500 mt-2">Browse our courses and start learning today!</p>
        <a href="{{ route('courses.index') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white py-2 px-6 rounded mt-4">
            Browse Courses
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($enrollments as $enrollment)
        @php $course = $enrollment->course @endphp
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $course->title }}</h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($course->description, 100) }}</p>
                
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-play-circle mr-1"></i>
                        <span>{{ $course->episodes_count }} episodes</span>
                    </div>
                    @if($enrollment->isCompleted())
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Completed</span>
                    @else
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">In Progress</span>
                    @endif
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">By {{ $course->user->name }}</span>
                    <a href="{{ route('courses.show', $course) }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm">
                        Continue
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection