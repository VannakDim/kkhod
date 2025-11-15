<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function enroll(Course $course)
    {
        // Check if already enrolled
        if (Auth::user()->enrolledCourses->contains($course->id)) {
            return redirect()->route('courses.show', $course)->with('info', 'You are already enrolled in this course.');
        }

        // For now, free enrollment. You can add payment logic here later
        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
        ]);

        return redirect()->route('courses.show', $course)->with('success', 'Successfully enrolled in the course!');
    }

    public function unenroll(Course $course)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->route('courses.show', $course)->with('success', 'Successfully unenrolled from the course.');
        }

        return redirect()->route('courses.show', $course)->with('error', 'You are not enrolled in this course.');
    }

    public function myCourses()
    {
        $enrollments = Auth::user()->enrollments()->with('course')->get();
        return view('enrollments.my-courses', compact('enrollments'));
    }
}