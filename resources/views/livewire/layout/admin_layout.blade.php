<!-- This layout component is meant to be used inside a page layout (e.g. x-layouts.app) -->
<div>

        <!-- Mobile Toggle Button -->
        <button wire:click="$dispatch('toggleSidebar', to: 'sidebar')"
                class="fixed top-3 left-3 z-50 text-gray-300 hover:bg-gray-800
                    focus:ring-2 focus:ring-gray-700 rounded-lg text-sm p-2">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                    d="M5 7h14M5 12h14M5 17h10"/>
            </svg>
        </button>

        <!-- Sidebar -->
        <livewire:sidebar />

        <!-- Main Content -->
        <div id="main-content" class="transition-all duration-300 ml-0 lg:ml-64 p-4">
            {{ $slot }}
        </div>

    </div>
    <!-- ✅ END WRAPPER -->

    <script src='{{ asset("js/layout/admin_layout.js") }}'></script>
</div>