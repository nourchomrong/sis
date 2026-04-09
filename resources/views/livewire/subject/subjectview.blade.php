<div class="relative">
    <div x-data="{ show: false, message: '', timeout: null }" x-cloak
        x-init="
            window.addEventListener('subject-created', event => {
                const payload = event.detail;
                message = payload?.message || (Array.isArray(payload) ? payload[0]?.message : null) || (typeof payload === 'string' ? payload : '');
                if (!message) return;
                show = true;
                if (timeout) clearTimeout(timeout);
                timeout = setTimeout(() => { show = false; message = ''; }, 3000);
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

    <x-form.search label="Search Subjects" name="query" placeholder="Search by subject name or code..." />

    <div class="flex justify-end items-center mb-4">
        <button wire:click="openModal()"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Add Subject
        </button>
    </div>

       <div class="relative">
        
    <x-table :headers="['Subject Code', 'Subject Name', 'Description', 'Detail', 'Actions']">
        @forelse($subjects as $subject)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $subject->subject_code }}</td>
                <td class="px-4 py-2">{{ $subject->subject_name }}</td>
                <td class="px-4 py-2">{{ Str::limit($subject->description, 50) }}</td>
                <td class="px-4 py-2">
                    <button wire:click="viewSubject({{ $subject->subject_id }})"
                        class="bg-gray-500 text-white px-2 py-1 rounded">
                        View
                    </button>
                </td>
                <td class="px-4 py-2 flex gap-2">
                    <button wire:click="confirmEditSubject({{ $subject->subject_id }})"
                        class="bg-blue-500 text-white px-2 py-1 rounded">
                        Edit
                    </button>
                    <button wire:click="confirmDeleteSubject({{ $subject->subject_id }})"
                        class="bg-red-500 text-white px-2 py-1 rounded">
                        Delete
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center px-4 py-6 text-gray-500">No subjects found.</td>
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
        {{ $subjects->links() }}
    </div>

    @if($showSubjectDetail && $selectedSubject)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Subject Details</h2>
                <p class="mb-2"><strong>Code:</strong> {{ $selectedSubject->subject_code }}</p>
                <p class="mb-2"><strong>Name:</strong> {{ $selectedSubject->subject_name }}</p>
                <p class="mb-4"><strong>Description:</strong> {{ $selectedSubject->description ?? 'N/A' }}</p>
                <div class="flex justify-end">
                    <button wire:click="$set('showSubjectDetail', false)"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showDeleteConfirm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-semibold text-red-600 mb-4">Delete Subject</h2>
                <p>Are you sure you want to delete this subject? This action can be undone by re-activating it later.</p>
                <div class="mt-6 flex justify-end gap-2">
                    <button wire:click="cancelDelete"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="deleteSubject({{ $subjectToDelete }})"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showEditConfirm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Edit Subject</h2>
                <p>Do you want to edit this subject?</p>
                <div class="mt-6 flex justify-end gap-2">
                    <button wire:click="$set('showEditConfirm', false)"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="editSubjectConfirmed({{ $subjectToEdit }})"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Yes, Edit
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showSubjectForm)
        <livewire:subject.subjectform :subject-id="$selectedSubjectId" />
    @endif
</div>
