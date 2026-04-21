<div>
    <!-- Real-time polling script -->
    <script>
        (function() {
            // Poll for changes every 3 seconds
            setInterval(() => {
                // This triggers Livewire to refresh the component
                if (window.Livewire && Livewire.find) {
                    Livewire.dispatch('refresh-schedules');
                }
            }, 3000);
        })();
    </script>
    <!-- Toast container -->
    <div x-data="{ show: false, message: '', timeout: null }" x-cloak x-init="
            window.addEventListener('schedule-created', event => {
                const payload = event.detail;
                message =
                    payload?.message ||
                    (Array.isArray(payload) ? payload[0]?.message : null) ||
                    (typeof payload === 'string' ? payload : '');
                if (!message) return;
                show = true;
                if(timeout) clearTimeout(timeout);
                timeout = setTimeout(() => { show = false; message = '' }, 3000);
            })
        " x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed inset-x-0 top-6 flex justify-center z-50 px-4 sm:px-6 pointer-events-none">
        <div
            class="bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded shadow-lg w-full max-w-md text-center pointer-events-auto">
            <span x-text="message"></span>
        </div>
    </div>

    <x-form.search label="Search Schedule" name="query" placeholder="Search by class, subject, or day..." />
    
    <!-- Header + Add button -->
    <div class="flex justify-end items-center mb-4">
        <button wire:click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add Schedule
        </button>
    </div>

    <div class="relative">
       <!-- Schedule Table -->
         <x-table :headers="['Class', 'Subject', 'Teacher', 'Day', 'Time', 'Detail', 'Actions']">
            @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $schedule->class->class_name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $schedule->subject->subject_name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $schedule->teacher->en_fullname ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $schedule->day_of_week }}</td>
                    <td class="px-4 py-2">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i') }} - 
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i') }}
                    </td>
                    <td class="px-4 py-2">
                        <button wire:click="viewSchedule({{ $schedule->schedule_id }})"
                            class="bg-gray-500 text-white px-2 py-1 rounded">
                            View
                        </button>
                    </td>
                    <td class="px-4 py-2 flex gap-2">
                        <button wire:click="confirmEditSchedule({{ $schedule->schedule_id }})"
                            class="bg-blue-500 text-white px-2 py-1 rounded">
                            Edit
                        </button>
                        <button wire:click="confirmDeleteSchedule({{ $schedule->schedule_id }})"
                            class="bg-red-500 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">No schedules found.</td>
                </tr>
            @endforelse
        </x-table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $schedules->links() }}
        </div>

      <!-- 🔥 TABLE LOADING OVERLAY -->
    <div wire:loading.flex wire:target="query"
        class="absolute inset-0 z-10 items-center justify-center bg-white/70">

        <div class="px-4 py-2 bg-white border rounded shadow text-gray-700">
            Searching...
        </div>
    </div>

</div>

    <!-- Schedule Modal Form -->
    @if($showScheduleForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40" wire:key="schedule-form-modal">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-screen overflow-y-auto">
                <livewire:schedule.scheduleform :scheduleId="$selectedScheduleId" />
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if($showScheduleDetail && $selectedSchedule)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold mb-4">Schedule Details</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Class</label>
                        <p class="text-gray-900">{{ $selectedSchedule->class->class_name ?? 'N/A' }} (Grade {{ $selectedSchedule->class->grade_level ?? 'N/A' }})</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Subject</label>
                        <p class="text-gray-900">{{ $selectedSchedule->subject->subject_name ?? 'N/A' }} ({{ $selectedSchedule->subject->subject_code ?? 'N/A' }})</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Teacher</label>
                        <p class="text-gray-900">{{ $selectedSchedule->teacher->en_fullname ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Day of Week</label>
                        <p class="text-gray-900">{{ $selectedSchedule->day_of_week }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Time</label>
                        <p class="text-gray-900">
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $selectedSchedule->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $selectedSchedule->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('showScheduleDetail', false)"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6">
                <h3 class="text-lg font-bold text-red-600 mb-4">Confirm Delete</h3>
                <p class="text-gray-700 mb-6">Are you sure you want to delete this schedule? This action cannot be undone.</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="cancelDelete"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                        Cancel
                    </button>
                    <button wire:click="deleteSchedule({{ $scheduleToDelete }})"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
