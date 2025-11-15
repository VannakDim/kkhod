<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Episode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@learnplatform.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'bio' => 'Platform Administrator',
        ]);

        // Create instructor
        $instructor = User::create([
            'name' => 'John Instructor',
            'email' => 'instructor@learnplatform.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'bio' => 'Experienced web development instructor',
        ]);

        // Create student
        $student = User::create([
            'name' => 'Jane Student',
            'email' => 'student@learnplatform.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'bio' => 'Passionate learner',
        ]);

        // Create sample course
        $course = Course::create([
            'title' => 'Laravel Web Development',
            'slug' => 'laravel-web-development',
            'description' => 'Learn Laravel from scratch and build amazing web applications.',
            'outline' => "1. Introduction to Laravel\n2. Database and Eloquent\n3. Authentication & Authorization\n4. Building APIs\n5. Deployment",
            'price' => 99.99,
            'is_published' => true,
            'user_id' => $instructor->id,
        ]);

        // Create sample episodes
        Episode::create([
            'title' => 'Introduction to Laravel',
            'description' => 'Get familiar with Laravel framework and its features.',
            'duration' => 30,
            'order' => 1,
            'is_preview' => true,
            'course_id' => $course->id,
        ]);

        Episode::create([
            'title' => 'Database and Eloquent ORM',
            'description' => 'Learn how to work with databases using Eloquent.',
            'duration' => 45,
            'order' => 2,
            'is_preview' => false,
            'course_id' => $course->id,
        ]);

        Episode::create([
            'title' => 'Authentication System',
            'description' => 'Implement user registration and login functionality.',
            'duration' => 60,
            'order' => 3,
            'is_preview' => false,
            'course_id' => $course->id,
        ]);
    }
}