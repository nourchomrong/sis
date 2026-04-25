<div>
    <!-- Real-time polling script -->
    <script>
        (function() {
            // Poll for changes every 3 seconds
            setInterval(() => {
                // This triggers Livewire to refresh the component
                if (window.Livewire && Livewire.find) {
                    Livewire.dispatch('refresh-teachers');
                }
            }, 3000);
        })();
    </script>

    <!-- Toast container -->
    <div 
        x-data="{ show: false, message: '', timeout: null }" 
        x-cloak
        x-init="
            window.addEventListener('teacher-created', event => {
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
        "
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed inset-x-0 top-6 flex justify-center z-50 px-4 sm:px-6 pointer-events-none"
    >
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded shadow-lg w-full max-w-md text-center pointer-events-auto">
            <span x-text="message"></span>
        </div>
    </div>

   <x-form.search label="Search Teacher" name="query" placeholder="Type to search..." />

    <!-- Header + Add button -->
    <div class="flex justify-end items-center mb-4">
        <button wire:click="$toggle('showTeacherForm')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Add Teachers
        </button>
    </div>

        <!-- search -->
 

   <div class="relative">

    <!-- Teacher Table -->
    <x-table :headers="['Code','Name(En)','Name(KH)','Gender','Phone','Email','Detail','Actions']">

        @forelse($teachers as $teacher)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ str_pad($teacher->teacher_id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="px-4 py-2">{{ $teacher->en_fullname }}</td>
                <td class="px-4 py-2">{{ $teacher->kh_fullname }}</td>
                <td class="px-4 py-2">{{ $teacher->gender }}</td>
                <td class="px-4 py-2">{{ $teacher->phone }}</td>
                <td class="px-4 py-2">{{ $teacher->email }}</td>

                <td class="px-4 py-2">
                    <button wire:click="viewTeacher({{ $teacher->teacher_id }})"
                        class="bg-gray-500 text-white px-2 py-1 rounded">
                        View
                    </button>
                </td>

                <td class="px-4 py-2 flex gap-2">
                    <button wire:click="confirmEditTeacher({{ $teacher->teacher_id }})"
                        class="bg-blue-500 text-white px-2 py-1 rounded">
                        Edit
                    </button>

                    <button wire:click="confirmDeleteTeacher({{ $teacher->teacher_id }})"
                        class="bg-red-500 text-white px-2 py-1 rounded">
                        Delete
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-2 text-gray-500">
                    No data
                </td>
            </tr>
        @endforelse

    </x-table>

    <!-- 🔥 TABLE LOADING OVERLAY -->
    <div wire:loading.flex wire:target="query"
        class="absolute inset-0 z-10 items-center justify-center bg-white/70">

        <div class="px-4 py-2 bg-white border rounded shadow text-gray-700">
            Searching...
        </div>
    </div>

</div>

    <div class="mt-4">
        {{ $teachers->links() }}
    </div>

    @if($showTeacherDetail && $selectedTeacher)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">

                    <h2 class="text-xl font-bold mb-4">Teacher Detail</h2>
                    <p><strong>Profile Picture:</strong></p>
                    @if($selectedTeacher->photo && $selectedTeacher->photo->status === 'A')
                        <img src="{{ app(\App\Services\Setting::class)->urlForOwner($selectedTeacher) }}"
                            class="w-32 h-32 rounded object-cover mt-2 mb-4" alt="Teacher Photo">
                    @else
                        <div class="w-32 h-32 rounded bg-gray-200 flex items-center justify-center mt-2 mb-4">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                    <p><strong>Code:</strong> {{ str_pad($selectedTeacher->teacher_id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p><strong>Name (EN):</strong> {{ $selectedTeacher->en_fullname }}</p>
                    <p><strong>Name (KH):</strong> {{ $selectedTeacher->kh_fullname }}</p>
                    <p><strong>Gender:</strong> {{ $selectedTeacher->gender }}</p>
                    <p><strong>Date of Birth:</strong> {{ $selectedTeacher->dateofbirth }}</p>
                    <p><strong>Place of Birth:</strong> {{ $selectedTeacher->birthplace }}</p>
                    <p><strong>Address:</strong> {{ $selectedTeacher->address }}</p>
                    <p><strong>Phone:</strong> {{ $selectedTeacher->phone }}</p>
                    <p><strong>Email:</strong> {{ $selectedTeacher->email }}</p>
                    <p><strong>Created At:</strong> {{ $selectedTeacher->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $selectedTeacher->updated_at }}</p>

                    <div class="mt-4 text-right">
                        <button wire:click="$set('showTeacherDetail', false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm && $teacherToDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-red-600">Confirm Delete</h2>
                <p class="mb-4">Are you sure you want to delete this teacher? This action cannot be undone.</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="deleteTeacher({{ $teacherToDelete }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Confirmation Modal -->
    @if($showEditConfirm && $teacherToEdit)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-blue-600">Confirm Edit</h2>
                <p class="mb-4">Are you sure you want to edit this teacher's information?</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="editTeacherConfirmed({{ $teacherToEdit }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Conditionally show the teacher form -->
    @if($showTeacherForm)
        <livewire:teacher.teacherform :teacher-id="$selectedTeacherId" />
    @endif

    
</div>