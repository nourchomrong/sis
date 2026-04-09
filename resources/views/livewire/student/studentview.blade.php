<div>

    <!-- Toast container -->
    <div x-data="{ show: false, message: '', timeout: null }" x-cloak x-init="
            window.addEventListener('student-created', event => {
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
    <x-form.search label="Search Student" name="query" placeholder="Type to search..." />
    <!-- Header + Add button -->
    <div class="flex justify-end items-center mb-4">

        <button wire:click="$toggle('showStudentForm')"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Add Students
        </button>
    </div>

        

 <div class="relative">

    <!-- TABLE -->
    <x-table :headers="['Code', 'Name(En)', 'Name(KH)', 'Gender', 'Phone', 'Email', 'Detail', 'Actions']">
        @forelse($students as $student)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ str_pad($student->student_id, 5, '0', STR_PAD_LEFT) }}</td>
                <td class="px-4 py-2">{{ $student->en_fullname }}</td>
                <td class="px-4 py-2">{{ $student->kh_fullname }}</td>
                <td class="px-4 py-2">{{ $student->gender }}</td>
                <td class="px-4 py-2">{{ $student->phone }}</td>
                <td class="px-4 py-2">{{ $student->email }}</td>

                <td class="px-4 py-2">
                    <button wire:click="viewStudent({{ $student->student_id }})"
                        class="bg-gray-500 text-white px-2 py-1 rounded">
                        View
                    </button>
                </td>

                <td class="px-4 py-2 flex gap-2">
                    <button wire:click="confirmEditStudent({{ $student->student_id }})"
                        class="bg-blue-500 text-white px-2 py-1 rounded">
                        Edit
                    </button>

                    <button wire:click="confirmDeleteStudent({{ $student->student_id }})"
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

    <!-- 🔥 LOADING OVERLAY (ONLY TABLE) -->
    <div wire:loading.flex wire:target="query"
        class="absolute inset-0 z-10 items-center justify-center bg-white bg-opacity-70">
        
        <div class="px-4 py-2 bg-white border rounded shadow text-gray-700">
            Searching...
        </div>
    </div>

</div>

    <div class="mt-4">
        {{ $students->links() }}
    </div>

    @if($showStudentDetail && $selectedStudent)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">

                <h2 class="text-xl font-bold mb-4">Student Detail</h2>
                <p><strong>Profile Picture:</strong></p>
                @if($selectedStudent->photo && $selectedStudent->photo->status === 'A')
                    <img src="{{ asset('storage/' . $selectedStudent->photo->photo_path) }}"
                        class="w-32 h-32 rounded object-cover mt-2 mb-4" alt="Student Photo">
                @else
                    <div class="w-32 h-32 rounded bg-gray-200 flex items-center justify-center mt-2 mb-4">
                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @endif
                <p><strong>Code:</strong> {{ str_pad($selectedStudent->student_id, 5, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Name (EN):</strong> {{ $selectedStudent->en_fullname }}</p>
                <p><strong>Name (KH):</strong> {{ $selectedStudent->kh_fullname }}</p>
                <p><strong>Gender:</strong> {{ $selectedStudent->gender }}</p>
                <p><strong>Date of Birth:</strong> {{ $selectedStudent->dateofbirth }}</p>
                <p><strong>Place of Birth:</strong> {{ $selectedStudent->birthplace }}</p>
                <p><strong>Address:</strong> {{ $selectedStudent->address }}</p>
                <p><strong>Phone:</strong> {{ $selectedStudent->phone }}</p>
                <p><strong>Email:</strong> {{ $selectedStudent->email }}</p>
                <p><strong>Created At:</strong> {{ $selectedStudent->created_at }}</p>
                <p><strong>Updated At:</strong> {{ $selectedStudent->updated_at }}</p>

                <div class="mt-4 text-right">
                    <button wire:click="$set('showStudentDetail', false)" class="bg-gray-500 text-white px-4 py-2 rounded">
                        Close
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm && $studentToDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-red-600">Confirm Delete</h2>
                <p class="mb-4">Are you sure you want to delete this student? This action cannot be undone.</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="deleteStudent({{ $studentToDelete }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Confirmation Modal -->
    @if($showEditConfirm && $studentToEdit)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-blue-600">Confirm Edit</h2>
                <p class="mb-4">Are you sure you want to edit this student's information?</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="editStudent({{ $studentToEdit }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Conditionally show the student form -->
    @if($showStudentForm)
        <livewire:student.studentform :student-id="$selectedStudentId" />
    @endif

</div>