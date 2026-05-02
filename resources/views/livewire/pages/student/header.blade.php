<div>
    <div class="bg-white shadow w-full p-4">
        <div class="max-w-7xl mx-auto px-4 pr-0">
           <div class="flex justify-between items-center h-16">

                <!-- LEFT SIDE -->
                <div class="flex items-center">
                    <img src="{{ app(\App\Services\Setting::class)->logoUrl() }}" class="h-12 mr-4"/>

                    <div class="flex flex-col justify-center">
                        <h1 class="text-xl font-bold">Welcome Back.</h1>
                        <p>Testing.</p>
                    </div>
                </div>

                <!-- RIGHT SIDE (MENU) -->
                <div class="hidden md:flex items-center">
                    <livewire:pages.student.menu />
                </div>

            </div>
        </div>
    </div>
</div>