<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #0f0f0f;
            --secondary-bg: #1a1a1a;
            --accent-red: #e63946;
            --text-primary: #f8f9fa;
            --text-secondary: #adb5bd;
        }
        
        body {
            background-color: var(--primary-bg);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background-color: var(--secondary-bg);
            border-right: 1px solid #333;
        }
        
        .nav-link {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--accent-red);
            transform: translateX(5px);
        }
        
        .btn-primary {
            background-color: var(--accent-red);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #c1121f;
            transform: translateY(-2px);
        }
        
        .task-card {
            background-color: var(--secondary-bg);
            border-left: 4px solid var(--accent-red);
            transition: all 0.3s ease;
        }
        
        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }
        
        .completed {
            opacity: 0.7;
            border-left-color: #4caf50;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="sidebar w-64 flex-shrink-0 p-4 hidden md:block">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-red-500">Todo<span class="text-white">App</span></h1>
        </div>
        
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 nav-link rounded-lg @if(request()->routeIs('dashboard')) text-red-500 @endif">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tasks.index') }}" class="flex items-center p-2 nav-link rounded-lg @if(request()->routeIs('tasks.*')) text-red-500 @endif">
                        <i class="fas fa-tasks mr-3"></i>
                        <span>My Tasks</span>
                    </a>
                </li>
               
            </ul>
            
            <div class="mt-8 pt-4 border-t border-gray-700">
                <div class="flex items-center p-2">
                    <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center mr-3">
                        <span class="text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="flex items-center p-2 nav-link rounded-lg w-full text-left">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
    
    <!-- Mobile sidebar toggle -->
    <div class="md:hidden fixed top-4 left-4 z-50">
        <button id="sidebarToggle" class="p-2 rounded-lg bg-gray-800 text-white">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Mobile sidebar -->
    <div id="mobileSidebar" class="sidebar fixed inset-0 z-40 w-64 transform -translate-x-full md:hidden transition-transform duration-300 ease-in-out">
        <!-- Mobile sidebar content same as desktop -->
    </div>
    
    <!-- Main content -->
    <div class="flex-1 overflow-auto">
        <div class="p-6">
            @yield('content')
        </div>
    </div>
    
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('mobileSidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>