<x-layouts.app title="admin" :showLoader="false">
    <!-- Header -->
<header class="fixed top-0 left-0 right-0 h-16 bg-gray-800 border-b border-gray-700 flex items-center px-4 z-30">
    
    <!-- Sidebar toggle (mobile only) -->
    <button id="header-toggle" class="text-gray-300 hover:bg-gray-700 rounded-lg p-2 inline-flex sm:hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Logo & Title -->
    <div class="flex items-center ms-4">
        <img src="https://flowbite.com/docs/images/logo.svg" class="h-6 me-3" />
        <span class="text-white font-semibold text-lg">My Dashboard</span>
    </div>

    <!-- Spacer -->
    <div class="flex-1"></div>

    <!-- User / Profile Icon -->
    <div class="flex items-center gap-4">
        <button class="text-gray-300 hover:bg-gray-700 p-2 rounded-full">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
            </svg>
        </button>
    </div>
</header>
</x-layouts.app>
