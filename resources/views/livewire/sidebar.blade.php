
<div>
<!-- Mobile Toggle Button -->
<button wire:click="$dispatch('toggleSidebar')"
        class="fixed top-3 left-3 z-50 text-gray-300 hover:bg-gray-800
            focus:ring-2 focus:ring-gray-700 rounded-lg text-sm p-2 lg:hidden">
    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
            d="M5 7h14M5 12h14M5 17h10"/>
    </svg>
</button>

<aside id="logo-sidebar"
       class="fixed top-0 left-0 z-50 h-screen w-64 bg-gray-800 border-r border-gray-700
              transform transition-transform duration-300
              {{ $sidebarOpen ? 'translate-x-0' : '-translate-x-full' }} lg:translate-x-0">

    <div class="h-full px-3 py-4 overflow-y-auto hide-scrollbar">

            <!-- Logo -->
            <a href="#" class="flex items-center mb-5">
                <img src="{{ app(\App\Services\Setting::class)->logoUrl() }}" class="h-6 mr-3"/>
                <span class="sidebar-text text-lg font-semibold text-white">Student Info System</span>
            </a>

 <ul class="space-y-2">

            @foreach ($menuItems as $item)

                <!-- Single Link -->
                @if (!isset($item['children']))
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="block p-2 rounded hover:bg-gray-300 text-white flex items-center">
                            {!! $item['icon'] ?? '' !!}
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endif

                <!-- Dropdown -->
                @if (isset($item['children']))
                    <li>
                        <button wire:click="toggleMenu('{{ $item['key'] }}')"
                                class="w-full text-left p-2 hover:bg-gray-300 rounded flex justify-between items-center text-white">
                            <span class="flex items-center">
                                {!! $item['icon'] ?? '' !!}
                                {{ $item['title'] }}
                            </span>
                            <span>
                                {{ $this->isExpanded($item['key']) ? '-' : '+' }}
                            </span>
                        </button>

                        <ul class="ml-4 overflow-hidden transition-all duration-300"
                            style="max-height: {{ $this->isExpanded($item['key']) ? '500px' : '0px' }}">

                            @foreach ($item['children'] as $child)
                                <li>
                                    <a href="{{ $child['route'] ?? '#' }}"
                                    class="flex items-center p-2 rounded text-white hover:bg-gray-700 hover:text-yellow-300">
                                        {!! $child['icon'] ?? '' !!}
                                        {{ $child['title'] }}
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </li>
                @endif

            @endforeach

        </ul>
        </div>
    </aside>

    <!-- Mobile Overlay -->
    @if($sidebarOpen)
        <div wire:click="$dispatch('closeSidebar')" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>
    @endif
</div>