<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use League\CommonMark\CommonMarkConverter;

class CourseController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth')->except(['index', 'show']);
    // }

    public function index()
    {
        // return view('courses.create');
        $courses = Course::where('is_published', true)->with('user')->latest()->get();

        return view('courses.index', compact('courses'));
    }

    // public function show(Episode $episode)
    // {
    //     $converter = new CommonMarkConverter([
    //         'html_input' => 'allow',
    //         'allow_unsafe_links' => false,
    //     ]);

    //     $episode->html = $converter->convertToHtml($episode->description);

    //     return view('episodes.show', compact('episode'));
    // }

    public function create()
    {
        // $this->authorize('create', Course::class);
        return view('courses.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Course::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'outline' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');

            // Resize image
            // $image = Image::make(storage_path("app/public/{$path}"));
            // $image->fit(400, 300);
            // $image->save();

            $validated['thumbnail'] = $path;
        }

        $validated['user_id'] = auth()->id();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);

        Course::create($validated);

        return redirect()->route('courses.index')->with('success', 'Course created successfully!');
    }

    public function show(Course $course)
    {
        if (!$course->is_published && !auth()->user()?->can('update', $course)) {
            abort(404);
        }

        $course->load(['episodes', 'user']);
        $converter = new CommonMarkConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);
        $course->outline_html = $converter->convertToHtml($course->outline);
        $isEnrolled = auth()->check() && auth()->user()->enrolledCourses->contains($course->id);

        return view('courses.show', compact('course', 'isEnrolled'));
    }


    public function edit(Course $course)
    {
        // $this->authorize('update', $course);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'outline' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $path = $request->file('thumbnail')->store('thumbnails', 'public');

            // create image manager with desired driver
            $manager = new ImageManager(new Driver());

            // read image from file system
            $image = $manager->read(storage_path("app/public/{$path}"));

            // resize image proportionally to 300px width
            $image->scale(width: 300);

            $validated['thumbnail'] = $path;
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course)->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        // $this->authorize('delete', $course);

        // Delete associated files
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
    }
}
