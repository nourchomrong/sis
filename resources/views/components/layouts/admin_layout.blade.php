<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</head>

<body class="bg-gray-900 text-gray-200">

    <!-- Mobile Toggle Button -->
<button id="mobile-toggle" class="text-gray-300 hover:bg-gray-800 focus:ring-2 focus:ring-gray-700 rounded-lg ms-3 mt-3 text-sm p-2 inline-flex z-50 fixed">
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
        </svg>
    </button>

    <!-- Mobile Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 sm:hidden"></div>

<!-- Sidebar -->
<aside id="logo-sidebar"
       class="fixed top-0 left-0 z-50 h-screen w-64 bg-gray-800 border-r border-gray-700
              transform -translate-x-full transition-transform duration-300">

        <div class="h-full px-3 py-4 overflow-y-auto">
            <a href="#" class="flex items-center mb-5">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-6 me-3" />
                <span class="sidebar-text text-lg font-semibold text-white">Flowbite</span>
            </a>

            <ul class="space-y-2 font-medium">
                <li><a href="#" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <path stroke="currentColor" stroke-width="2" d="M16 12H4" /> </svg>
                    <span class="sidebar-text">Dashboard</span></a></li>
               <li><a href="#" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <path stroke="currentColor" stroke-width="2" d="M16 12H4" /> </svg>
                    <span class="sidebar-text">Dashboard</span></a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <path stroke="currentColor" stroke-width="2" d="M16 12H4" /> </svg>
                    <span class="sidebar-text">Dashboard</span></a></li>
                    <li><a href="#" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <path stroke="currentColor" stroke-width="2" d="M16 12H4" /> </svg>
                    <span class="sidebar-text">Dashboard</span></a></li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <div id="main-content" class="transition-all duration-300 ml-0 sm:ml-64 p-4 pt-16">
        <p class="text-2xl font-bold">Welcome to the Admin Dashboard</p>
    </div>
<script src='js/layout/admin_layout.js'></script>
</body>
    
</html>