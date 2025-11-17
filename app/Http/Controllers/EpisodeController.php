<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EpisodeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function create(Course $course)
    {
        // $this->authorize('update', $course);
        return view('episodes.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        // $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|file|mimes:mp4,mov,avi|max:512000', // 500MB max
            'file_attachments' => 'nullable|file|max:10240', // 10MB max
            'duration' => 'required|integer|min:1',
            'order' => 'required|integer',
            'is_preview' => 'boolean',
        ]);

        // Handle video upload
        $videoPath = $request->file('video')->store('videos', 'public');
        $validated['video_url'] = $videoPath;

        // Handle file attachments
        if ($request->hasFile('file_attachments')) {
            $filePath = $request->file('file_attachments')->store('attachments', 'public');
            $validated['file_attachments'] = $filePath;
        }

        $validated['course_id'] = $course->id;

        Episode::create($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Episode added successfully!');
    }

    public function edit(Course $course, Episode $episode)
    {
        // $this->authorize('update', $course);
        return view('episodes.edit', compact('course', 'episode'));
    }

    public function update(Request $request, Course $course, Episode $episode)
    {
        // $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:512000',
            'file_attachments' => 'nullable|file|max:10240',
            'duration' => 'required|integer|min:1',
            'order' => 'required|integer',
            'is_preview' => 'boolean',
        ]);

        // Handle video upload
        if ($request->hasFile('video')) {
            // Delete old video
            if ($episode->video_url) {
                Storage::disk('public')->delete($episode->getRawOriginal('video_url'));
            }

            $videoPath = $request->file('video')->store('videos', 'public');
            $validated['video_url'] = $videoPath;
        }

        // Handle file attachments
        if ($request->hasFile('file_attachments')) {
            // Delete old file
            if ($episode->file_attachments) {
                Storage::disk('public')->delete($episode->getRawOriginal('file_attachments'));
            }

            $filePath = $request->file('file_attachments')->store('attachments', 'public');
            $validated['file_attachments'] = $filePath;
        }

        $episode->update($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Episode updated successfully!');
    }

    // public function destroy(Course $course, Episode $episode)
    // {
    //     $this->authorize('update', $course);

    //     // Delete associated files
    //     if ($episode->video_url) {
    //         Storage::disk('public')->delete($episode->getRawOriginal('video_url'));
    //     }
    //     if ($episode->file_attachments) {
    //         Storage::disk('public')->delete($episode->getRawOriginal('file_attachments'));
    //     }

    //     $episode->delete();

    //     return redirect()->route('courses.show', $course)->with('success', 'Episode deleted successfully!');
    // }

    public function show(Course $course, Episode $episode)
    {
        $isEnrolled = auth()->check() && auth()->user()->enrolledCourses->contains($course->id);

        if (!$isEnrolled && !$episode->is_preview && !auth()->user()?->can('update', $course)) {
            abort(403, 'You need to enroll in this course to view this episode.');
        }

        $nextEpisode = $course->episodes()
            ->where('order', '>', $episode->order)
            ->orderBy('order')
            ->first();

        $prevEpisode = $course->episodes()
            ->where('order', '<', $episode->order)
            ->orderBy('order', 'desc')
            ->first();

        return view('episodes.show', compact('course', 'episode', 'nextEpisode', 'prevEpisode', 'isEnrolled'));
    }

}
