<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn KKHODD - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/kkhod.png') }}">
    <style>
        .code-block {
            position: relative;
        }

        .copy-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #2d2d2d;
            color: #fff;
            padding: 4px 8px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            opacity: 0.6;
            transition: 0.2s;
        }

        .copy-btn:hover {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Nav -->
    <nav class="bg-white shadow-lg" x-data="{ open: false, dropdown: false }">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-0">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 text-xl font-bold text-gray-800">
                    {{-- <img src="{{ asset('storage/logo/logo-transparency.png') }}" alt="Logo" class="h-20 w-20"> --}}
                    <span>K KHOD</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('courses.index') }}" class="text-gray-600 hover:text-blue-500">Courses</a>

                    @auth
                        @if (auth()->user()->isInstructor())
                            <a href="{{ route('courses.create') }}" class="text-gray-600 hover:text-blue-500">
                                Create Course
                            </a>
                        @endif

                        <a href="{{ route('enrollments.my-courses') }}" class="text-gray-600 hover:text-blue-500">
                            My Courses
                        </a>

                        <!-- Dropdown -->
                        <div class="relative" @mouseenter="dropdown = true" @mouseleave="dropdown = false">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-500">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div x-show="dropdown" x-transition
                                class="absolute right-0 w-48 bg-white shadow-lg rounded-md py-2 z-50">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 text-gray-700">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-500">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden focus:outline-none" @click="open = !open">
                    <i class="fas fa-bars text-xl text-gray-700"></i>
                </button>

            </div>

            <!-- Mobile Menu -->
            <div x-show="open" x-transition class="md:hidden pb-4 space-y-3">

                <a href="{{ route('courses.index') }}" class="block text-gray-600">Courses</a>

                @auth
                    @if (auth()->user()->isInstructor())
                        <a href="{{ route('courses.create') }}" class="block text-gray-600">Create Course</a>
                    @endif

                    <a href="{{ route('enrollments.my-courses') }}" class="block text-gray-600">My Courses</a>

                    <hr>

                    <a href="{{ route('profile.edit') }}" class="block text-gray-600">Profile</a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="block w-full text-left text-gray-600 py-2">Logout</button>
                    </form>

                @else
                    <a href="{{ route('login') }}" class="block text-gray-600">Login</a>
                    <a href="{{ route('register') }}"
                        class="block bg-blue-500 text-white px-4 py-2 rounded w-max">Register</a>
                @endauth

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12 w-full"
        x-data
        x-init="
            if (document.body.offsetHeight < window.innerHeight) {
                $el.style.position = 'fixed';
                $el.style.left = '0';
                $el.style.bottom = '0';
            } else {
                $el.style.position = 'static';
            }
        ">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} LearnPlatform. All rights reserved.</p>
        </div>
    </footer>

    <!-- Code Highlight + Copy -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll("pre code").forEach((codeBlock) => {
                const pre = codeBlock.parentElement;
                pre.classList.add("code-block");

                const button = document.createElement("button");
                button.className = "copy-btn";
                button.textContent = "Copy";

                button.addEventListener("click", () => {
                    navigator.clipboard.writeText(codeBlock.innerText).then(() => {
                        button.textContent = "Copied!";
                        setTimeout(() => button.textContent = "Copy", 2000);
                    });
                });

                pre.appendChild(button);
            });
        });
    </script>

</body>
</html>
