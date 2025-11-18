@extends('layouts.app')

@section('title', $course->title)

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Course Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="md:flex">
                <div class="md:flex-shrink-0 md:w-1/3">
                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}"
                        class="h-64 w-full object-cover md:h-full">
                </div>
                <div class="p-8 md:w-2/3">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $course->title }}</h1>
                    <p class="text-gray-600 mb-6">{{ $course->description }}</p>

                    <div class="flex items-center space-x-6 mb-6">
                        <div class="flex items-center text-gray-500">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span>{{ $course->user->name }}</span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <i class="fas fa-play-circle mr-2"></i>
                            <span>{{ $course->episodes_count }} episodes</span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $course->total_duration }} min</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($course->price, 2) }}</span>

                        @auth
                            @if (auth()->user()->id === $course->user_id || auth()->user()->isAdmin())
                                <div class="space-x-2">
                                    <a href="{{ route('episodes.create', $course) }}"
                                        class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded">
                                        Add Episode
                                    </a>
                                    <a href="{{ route('courses.edit', $course) }}"
                                        class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">
                                        Edit Course
                                    </a>
                                </div>
                            @elseif($isEnrolled)
                                <span class="bg-green-100 text-green-800 py-2 px-4 rounded-full">
                                    Enrolled
                                </span>
                            @else
                                <form action="{{ route('enrollments.enroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-6 rounded">
                                        Enroll Now
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-6 rounded">
                                Login to Enroll
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Episodes List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Course Content</h2>

                    @if ($course->outline)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3">Course Outline</h3>
                            <div class="episode-content prose max-w-none text-gray-600">
                                {!! $course->outline_html !!}
                            </div>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @foreach ($course->episodes as $episode)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-play text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">{{ $episode->title }}</h4>
                                            <p class="text-sm text-gray-500">{{ $episode->formatted_duration }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if ($episode->is_preview)
                                            <span
                                                class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Preview</span>
                                        @endif
                                        @auth
                                            @if ($isEnrolled || auth()->user()->id === $course->user_id || auth()->user()->isAdmin())
                                                <a href="{{ route('courses.episodes.show', [$course, $episode]) }}"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    Watch <i class="fas fa-arrow-right ml-1"></i>
                                                </a>
                                            @elseif($episode->is_preview)
                                                <a href="{{ route('courses.episodes.show', [$course, $episode]) }}"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    Preview <i class="fas fa-arrow-right ml-1"></i>
                                                </a>
                                            @else
                                                <span class="text-gray-400">Enroll to watch</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">Login to watch</span>
                                        @endauth
                                    </div>
                                </div>
                                @if ($episode->description)
                                    <p class="text-sm text-gray-600 mt-2 ml-16">
                                        {{ Str::limit($episode->description, 150) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if ($course->episodes->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-video-slash text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-600">No episodes yet</h3>
                            <p class="text-gray-500">Episodes will be added soon</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Instructor Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Instructor</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $course->user->name }}</h4>
                            <p class="text-sm text-gray-500">Course Instructor</p>
                        </div>
                    </div>
                </div>

                <!-- Course Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Course Details</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Episodes</span>
                            <span class="font-semibold">{{ $course->episodes_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-semibold">{{ $course->total_duration }} min</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Price</span>
                            <span class="font-semibold text-blue-600">${{ number_format($course->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
