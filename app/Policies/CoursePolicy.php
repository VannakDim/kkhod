<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Course $course): bool
    {
        return $course->is_published || $user->id === $course->user_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isInstructor() || $user->isAdmin();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->id === $course->user_id || $user->isAdmin();
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->id === $course->user_id || $user->isAdmin();
    }
}