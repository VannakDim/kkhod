<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn Platform - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div class="flex items-center py-4">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">K KHOD</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('courses.index') }}" class="py-4 px-2 text-gray-500 hover:text-blue-500">Courses</a>
                        @auth
                            @if(auth()->user()->isInstructor())
                                <a href="{{ route('courses.create') }}" class="py-4 px-2 text-gray-500 hover:text-blue-500">Create Course</a>
                            @endif
                            <a href="{{ route('enrollments.my-courses') }}" class="py-4 px-2 text-gray-500 hover:text-blue-500">My Courses</a>
                        @endauth
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-500">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 hidden group-hover:block z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="py-2 px-4 text-gray-700 hover:text-blue-500">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} LearnPlatform. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Simple dropdown functionality
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.group')) {
                document.querySelectorAll('.group-hover\\:block').forEach(function(el) {
                    el.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>