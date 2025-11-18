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
            <a href="{{ route('courses.show', $course) }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-6 rounded">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-6 rounded">
                Add Episode
            </button>
        </div>
    </form>
</div>

<!-- Upload Progress Modal -->
<div id="uploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Uploading Episode</h3>
            <div class="mt-4 px-7 py-3">
                <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                    <div id="progressBar" class="bg-blue-600 h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="progressText" class="text-sm text-gray-600 mb-2">0% Complete</p>
                <p id="uploadSpeed" class="text-sm text-gray-500">Speed: -- MB/s</p>
                <p id="timeRemaining" class="text-sm text-gray-500">Time remaining: Calculating...</p>
                <p id="fileSize" class="text-sm text-gray-500 mt-2">Uploaded: 0 MB / 0 MB</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('episodeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const modal = document.getElementById('uploadModal');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const uploadSpeed = document.getElementById('uploadSpeed');
    const timeRemaining = document.getElementById('timeRemaining');
    const fileSize = document.getElementById('fileSize');
    
    // Show modal
    modal.classList.remove('hidden');
    
    let startTime = Date.now();
    let uploadedLast = 0;
    
    const xhr = new XMLHttpRequest();
    
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            const currentTime = Date.now();
            const elapsedTime = (currentTime - startTime) / 1000; // seconds
            
            // Update progress bar
            progressBar.style.width = percentComplete + '%';
            progressText.textContent = Math.round(percentComplete) + '% Complete';
            
            // Calculate speed
            const uploadedNow = e.loaded;
            const speed = (uploadedNow - uploadedLast) / 1024 / 1024; // MB/s
            uploadedLast = uploadedNow;
            uploadSpeed.textContent = 'Speed: ' + speed.toFixed(2) + ' MB/s';
            
            // Calculate time remaining
            const bytesRemaining = e.total - e.loaded;
            const secondsRemaining = bytesRemaining / (uploadedNow / elapsedTime);
            
            if (secondsRemaining > 60) {
                const minutes = Math.floor(secondsRemaining / 60);
                const seconds = Math.floor(secondsRemaining % 60);
                timeRemaining.textContent = 'Time remaining: ' + minutes + 'm ' + seconds + 's';
            } else {
                timeRemaining.textContent = 'Time remaining: ' + Math.floor(secondsRemaining) + 's';
            }
            
            // Update file size
            const loadedMB = (e.loaded / 1024 / 1024).toFixed(2);
            const totalMB = (e.total / 1024 / 1024).toFixed(2);
            fileSize.textContent = 'Uploaded: ' + loadedMB + ' MB / ' + totalMB + ' MB';
        }
    });
    
    xhr.addEventListener('load', function() {
        if (xhr.status === 200 || xhr.status === 302) {
            progressText.textContent = 'Upload Complete! Redirecting...';
            const response = JSON.parse(xhr.responseText);
            if (response.redirect) {
                window.location.href = response.redirect;
            } else {
                window.location.href = form.action.replace('/episodes', '');
            }
        } else {
            alert('Upload failed. Please try again.');
            modal.classList.add('hidden');
        }
    });
    
    xhr.addEventListener('error', function() {
        alert('Upload error. Please check your connection and try again.');
        modal.classList.add('hidden');
    });
    
    xhr.open('POST', form.action);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.send(formData);
});
</script>
@endpush
@endsection