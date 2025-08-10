<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f0f0f;
            color: #f8f9fa;
        }
        
        .bg-gray-800 {
            background-color: #1a1a1a;
        }
        
        .bg-gray-850 {
            background-color: #151515;
        }
        
        .bg-gray-900 {
            background-color: #0f0f0f;
        }
        
        .border-gray-700 {
            border-color: #333;
        }
        
        .text-gray-300 {
            color: #d1d5db;
        }
        
        .text-gray-400 {
            color: #9ca3af;
        }
        
        .focus\:border-red-500:focus {
            border-color: #e63946;
        }
        
        .hover\:bg-red-600:hover {
            background-color: #c1121f;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>