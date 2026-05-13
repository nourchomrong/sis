<div>
    <div class="bg-white shadow w-full p-4">
        <div class="mx-auto px-4 pr-0">

            <div class="flex justify-between items-center h-16">

                <!-- LEFT SIDE -->
                <div class="flex items-center">
                    <img src="{{ app(\App\Services\Setting::class)->logoUrl() }}" class="h-12 mr-4"/>

                    <div class="flex flex-col justify-center">
                        <h1 class="text-xl font-bold">Welcome Back.</h1>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="flex items-center space-x-6">

                    <!-- USER INFO -->
                    <div class="flex items-center space-x-3">

                        <img src="{{ asset('assets/images/dprofile.png') }}" class="h-8 border-2 border-grey-500 rounded-full"/>

                        <div class="flex flex-col leading-tight">
                            <p class="text-sm font-medium">
                                @if(auth()->user()->owner)
                                    {{ auth()->user()->owner->en_fullname }} | {{ auth()->user()->owner->kh_fullname }}
                                @else
                                    {{ auth()->user()->username }}
                                @endif
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ auth()->user()->role?->role_type }}
                            </p>
                            
                        </div>
                        <livewire:pages.student.menu />

                </div>

            </div>
        </div>
    </div>
</div>