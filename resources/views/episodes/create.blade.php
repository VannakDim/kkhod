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

    <!-- Progress Indicator (Hidden by default) -->
    <div id="uploadProgress" class="hidden bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="mb-4">
            <div class="flex justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Uploading video...</span>
                <span class="text-sm font-medium text-gray-700" id="progressPercent">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div id="progressBar" class="bg-blue-600 h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>
        <p class="text-sm text-gray-600" id="progressStatus">Please wait while your video is being uploaded...</p>
    </div>

    <form id="episodeForm" action="{{ route('courses.episodes.store', $course) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
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
            <a href="{{ route('courses.show', $course) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-6 rounded" id="cancelBtn">
                Cancel
            </a>
            <button type="submit" id="submitBtn" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-6 rounded">
                Add Episode
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('episodeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const progressDiv = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressStatus = document.getElementById('progressStatus');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    
    // Show progress bar and disable buttons
    progressDiv.classList.remove('hidden');
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    cancelBtn.classList.add('pointer-events-none', 'opacity-50');
    
    const xhr = new XMLHttpRequest();
    
    // Upload progress
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressPercent.textContent = percentComplete + '%';
        }
    });
    
    // Upload complete
    xhr.addEventListener('load', function() {
        if (xhr.status === 200 || xhr.status === 302) {
            progressStatus.textContent = 'Upload complete! Redirecting...';
            progressBar.classList.remove('bg-blue-600');
            progressBar.classList.add('bg-green-600');
            
            // Handle redirect
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            } catch {
                // If not JSON, browser will handle redirect
                window.location.href = "{{ route('courses.show', $course) }}";
            }
        } else {
            progressStatus.textContent = 'Upload failed. Please try again.';
            progressBar.classList.remove('bg-blue-600');
            progressBar.classList.add('bg-red-600');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            cancelBtn.classList.remove('pointer-events-none', 'opacity-50');
        }
    });
    
    // Upload error
    xhr.addEventListener('error', function() {
        progressStatus.textContent = 'Upload failed. Please try again.';
        progressBar.classList.remove('bg-blue-600');
        progressBar.classList.add('bg-red-600');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        cancelBtn.classList.remove('pointer-events-none', 'opacity-50');
    });
    
    xhr.open('POST', this.action);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(formData);
});
</script>
@endpush
@endsection