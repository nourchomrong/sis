<div class="relative">

    <!-- ✅ Toast -->
    <div x-data="{ show: false, message: '', timeout: null }" x-cloak
        x-init="
            window.addEventListener('classes-created', event => {
                const payload = event.detail;
                message =
                    payload?.message ||
                    (Array.isArray(payload) ? payload[0]?.message : null) ||
                    (typeof payload === 'string' ? payload : '');
                if (!message) return;

                show = true;

                if (timeout) clearTimeout(timeout);
                timeout = setTimeout(() => {
                    show = false;
                    message = '';
                }, 3000);
            })
        "
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed inset-x-0 top-6 flex justify-center z-50 px-4 pointer-events-none">

        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded shadow-lg w-full max-w-md text-center pointer-events-auto">
            <span x-text="message"></span>
        </div>
    </div>

    <!-- ✅ Search -->
    <x-form.search label="Search Classes" name="query" placeholder="Type to search..." />

    <!-- ✅ Header -->
    <div class="flex justify-end items-center mb-4">
        <button wire:click="$toggle('showClassesForm')"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Add Classes
        </button>
    </div>

    <!-- ✅ TABLE + LOADING -->
    <div class="relative">

        <x-table :headers="['Class Name', 'Grade Level', 'Year', 'Classroom', 'Schedule', 'Student', 'Instructor', 'Actions']">
            @forelse($classes as $class)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $class->class_name }}</td>
                    <td class="px-4 py-2">{{ $class->grade_level }}</td>
                    <td class="px-4 py-2">{{ $class->year->year_name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        <button wire:click="viewClassroom({{ $class->classroom_id }})"
                            class="bg-gray-500 text-white px-2 py-1 rounded">
                            View Class
                        </button>
                    </td>
                    <td class="px-4 py-2">
                        <button wire:click="viewSchedule({{ $class->class_id }})"
                            class="bg-gray-500 text-white px-2 py-1 rounded">
                            View Schedule
                        </button>
                    </td>
                    <td class="px-4 py-2">
                        <button wire:click="viewStudents({{ $class->class_id }})"
                            class="bg-gray-500 text-white px-2 py-1 rounded">
                            View Student
                        </button>
                    <td class="px-4 py-2">{{ $class->teacher->en_fullname ?? 'N/A' }}</td>
                    <td class="px-4 py-2 flex gap-2">
                        <button wire:click="confirmEditClass({{ $class->class_id }})"
                            class="bg-blue-500 text-white px-2 py-1 rounded">
                            Edit
                        </button>
                        <button wire:click="confirmDeleteClass({{ $class->class_id }})"
                            class="bg-red-500 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        No classes found.
                    </td>
                </tr>
            @endforelse
        </x-table>

        <!-- 🔥 Loading overlay -->
        <div wire:loading.flex wire:target="query"
            class="absolute inset-0 z-10 items-center justify-center bg-white bg-opacity-70">
            <div class="px-4 py-2 bg-white border rounded shadow text-gray-700">
                Searching...
            </div>
        </div>

    </div>

    <!-- ✅ Pagination -->
    <div class="mt-4">
        {{ $classes->links() }}
    </div>

    <!-- ✅ CLASSROOM DETAILS MODAL -->
    @if($showClassesDetail && $selectedClasses)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

                <h2 class="text-xl font-bold mb-4">Class Details</h2>

                <p><strong>Class Name:</strong> {{ $selectedClasses->class_name }}</p>
                <p><strong>Grade Level:</strong> {{ $selectedClasses->grade_level }}</p>
               <p><strong>Year:</strong> 
                    {{ $selectedClasses->year?->year_name ?? 'N/A' }}
                    @if($selectedClasses->year)
                        ({{ $selectedClasses->year->start_date ?? 'N/A' }} - 
                        {{ $selectedClasses->year->end_date ?? 'N/A' }})
                    @endif
                </p>
                <p><strong>Classroom:</strong> {{ $selectedClasses->classroom->room_name ?? 'N/A' }}</p>
                <p><strong>Instructor:</strong> {{ $selectedClasses->teacher->en_fullname ?? 'N/A' }}</p>
                <p><strong>Created At:</strong> {{ $selectedClasses->created_at->format('M d, Y') }}</p>
                <p><strong>Updated At:</strong> {{ $selectedClasses->updated_at->format('M d, Y') }}</p>

                <button wire:click="$set('showClassesDetail', false)"
                    class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    @endif

    <!-- ✅ DELETE MODAL -->
    @if($showDeleteConfirm && $classesToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

                <h2 class="text-xl font-bold mb-4">Confirm Deletion</h2>
                <p>Are you sure you want to delete this class?</p>

                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="$set('showDeleteConfirm', false)"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>

                    <button wire:click="deleteClass"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ✅ EDIT CONFIRM MODAL -->
    @if($showEditConfirm && $classesToEdit)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">

                <h2 class="text-xl font-bold mb-4 text-blue-600">Confirm Edit</h2>
                <p class="mb-4">Are you sure you want to edit this class?</p>

                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelEdit"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>

                    <button wire:click="editClassesConfirmed({{ $classesToEdit }})"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ✅ SCHEDULE VIEW MODAL -->
    @if($showScheduleView && $selectedSchedules)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-6xl max-h-screen overflow-y-auto">

                <h2 class="text-xl font-bold mb-4">Schedules for Class</h2>
                <x-form.search label="Search Schedules" name="scheduleQuery" placeholder="Search by subject, teacher, day or time..." />
                <div class="flex justify-end items-center mb-4  ">
                    
                    <button wire:click="openScheduleForm"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Add Schedule
                    </button>
                </div>

                @php
                    $groupedSchedules = $filteredSchedules->groupBy('day_of_week')->map(function ($daySchedules) {
                        return $daySchedules->groupBy(function ($schedule) {
                            return \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('G');
                        })->sortKeys();
                    });
                @endphp

                @foreach($daysOrder as $day)
                    @if($groupedSchedules->has($day))
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2 text-gray-800">{{ $day }}</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white divide-y divide-gray-200 border">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Time</th>
                                            <th class="px-4 py-2 text-left">Subject</th>
                                            <th class="px-4 py-2 text-left">Teacher</th>
                                            <th class="px-4 py-2 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($groupedSchedules[$day] as $hour => $schedulesByHour)
                                            <tr class="bg-gray-100">
                                                <td colspan="4" class="px-4 py-2 text-sm font-semibold text-gray-700">
                                                    Hour {{ $hour == '0' ? '24' : $hour }}:00
                                                </td>
                                            </tr>
                                            @foreach($schedulesByHour as $schedule)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2">
                                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i') }}
                                                    </td>
                                                    <td class="px-4 py-2">{{ $schedule->subject->subject_name ?? 'N/A' }}</td>
                                                    <td class="px-4 py-2">{{ $schedule->teacher->en_fullname ?? 'N/A' }}</td>
                                                    <td class="px-4 py-2 flex gap-2">
                                                        <button wire:click="editSchedule({{ $schedule->schedule_id }})"
                                                            class="bg-blue-500 text-white px-2 py-1 rounded">
                                                            Edit
                                                        </button>
                                                        <button wire:click="confirmDeleteSchedule({{ $schedule->schedule_id }})"
                                                            class="bg-red-500 text-white px-2 py-1 rounded">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if($filteredSchedules->isEmpty())
                    <p class="text-center py-4 text-gray-500">No schedules found for this class.</p>
                @endif

                <button wire:click="closeScheduleView"
                    class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    @endif

    <!-- ✅ SCHEDULE FORM MODAL -->
    @if($showScheduleForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md max-h-screen overflow-y-auto">
                <livewire:schedule.scheduleform :schedule-id="$selectedScheduleId" :class-id="$selectedClassIdForSchedule" />
            </div>
        </div>
    @endif

    <!-- ✅ SCHEDULE DELETE CONFIRM MODAL -->
    @if($showScheduleDeleteConfirm && $scheduleToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

                <h2 class="text-xl font-bold mb-4 text-red-600">Confirm Delete</h2>
                <p class="mb-4">Are you sure you want to delete this schedule?</p>

                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelDeleteSchedule"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>

                    <button wire:click="deleteSchedule({{ $scheduleToDelete }})"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ✅ FORM -->
    @if($showClassesForm)
        <livewire:classes.classesform :classes-id="$selectedClassesId" />
    @endif

</div>