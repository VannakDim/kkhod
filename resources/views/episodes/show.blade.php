@extends('layouts.app')

@section('title', $episode->title . ' - ' . $course->title)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Navigation -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('courses.show', $course->slug) }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Course
            </a>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $course->title }}</h1>
        </div>
        
        <!-- Episode Navigation -->
        <div class="flex space-x-2">
            @if($prevEpisode)
                <a href="{{ route('courses.episodes.show', [$course, $prevEpisode]) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded text-sm">
                    <i class="fas fa-chevron-left mr-1"></i> Previous
                </a>
            @endif
            @if($nextEpisode)
                <a href="{{ route('courses.episodes.show', [$course, $nextEpisode]) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm">
                    Next <i class="fas fa-chevron-right ml-1"></i>
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Video Player -->
        <div class="lg:col-span-3">
            <div class="bg-black rounded-lg overflow-hidden">
                @if($episode->video_url)
                    <video controls class="w-full" poster="{{ $course->thumbnail_url }}">
                        <source src="{{ $episode->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="aspect-w-16 aspect-h-9 bg-gray-800 flex items-center justify-center">
                        <div class="text-center text-white">
                            <i class="fas fa-video-slash text-4xl mb-2"></i>
                            <p>Video not available</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Episode Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $episode->title }}</h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span><i class="fas fa-clock mr-1"></i> {{ $episode->formatted_duration }}</span>
                            @if($episode->is_preview)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Preview</span>
                            @endif
                        </div>
                        
                        @auth
                            @if(auth()->user()->is_admin || auth()->user()->id === $course->user_id)
                                <div class="flex space-x-2">
                                    <a href="{{ route('episodes.edit', [$course, $episode]) }}" 
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('episodes.destroy', [$course, $episode]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this episode?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                @if($episode->description)
                    <div class="prose max-w-none text-gray-600">
                        <div class="prose prose-slate max-w-none">
                            {!! Str::markdown($episode->description_html) !!}
                        </div>
                    </div>
                @endif

                <!-- File Attachments -->
                @if($episode->file_attachments)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Resources</h3>
                    <a href="{{ $episode->file_attachments }}" 
                       class="inline-flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 px-4 rounded-lg">
                        <i class="fas fa-download mr-2"></i>
                        Download Attachments
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Episodes List Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Episodes</h3>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($course->episodes as $courseEpisode)
                    <a href="{{ route('courses.episodes.show', [$course, $courseEpisode]) }}" 
                       class="block p-3 rounded-lg border {{ $episode->id === $courseEpisode->id ? 'bg-blue-50 border-blue-200' : 'border-gray-200 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-play text-blue-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate {{ $episode->id === $courseEpisode->id ? 'text-blue-700' : '' }}">
                                    {{ $courseEpisode->title }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $courseEpisode->formatted_duration }}</p>
                            </div>
                            @if($courseEpisode->is_preview)
                                <span class="flex-shrink-0 bg-green-100 text-green-800 text-xs px-1 rounded">Free</span>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection