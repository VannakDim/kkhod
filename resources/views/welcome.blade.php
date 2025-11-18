@extends('layouts.app')

@section('title', 'Welcome to LearnPlatform')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Hero Section -->
    <div class="text-center py-16">
        <h1 class="text-5xl font-bold text-gray-800 mb-6">Learn Without Limits</h1>
        <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
            Start, switch, or advance your career with our comprehensive courses taught by expert instructors.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-6">
            <a href="{{ route('courses.index') }}" class="w-full sm:w-auto text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 sm:px-8 rounded-lg text-base sm:text-lg">
                Browse Courses
            </a>

            @guest
            <a href="{{ route('register') }}" class="w-full sm:w-auto text-center bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 sm:px-8 rounded-lg text-base sm:text-lg">
                Start Learning Free
            </a>
            @endguest
        </div>
    </div>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 py-16">
        <div class="text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-video text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Video Lessons</h3>
            <p class="text-gray-600">Learn from high-quality video lectures with experienced instructors.</p>
        </div>
        
        <div class="text-center">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-code text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Code Resources</h3>
            <p class="text-gray-600">Download code files and resources to practice along with the lessons.</p>
        </div>
        
        <div class="text-center">
            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-certificate text-purple-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Expert Instructors</h3>
            <p class="text-gray-600">Learn from industry professionals with real-world experience.</p>
        </div>
    </div>

    <!-- Popular Courses Section -->
    <div class="py-16">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">Popular Courses</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
            @foreach($popularCourses as $course)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <a href="{{ route('courses.show', $course->id) }}">
                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            <a href="{{ route('courses.show', $course->id) }}">{{ $course->title }}</a>
                        </h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 80) }}</p>
                        <a href="{{ route('courses.show', $course->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            View Course
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('courses.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white py-3 px-8 rounded-lg">
                View All Courses
            </a>
        </div>
    </div>
</div>
@endsection