<div>
    
        <!-- Toast container -->
    <div x-data="{ show: false, message: '', timeout: null }" x-cloak x-init="
            window.addEventListener('academicyear-created', event => {
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
    <x-form.search label="Search Academic Year" name="query" placeholder="Type to search..." />
    <!-- Header + Add button -->
    <div class="flex justify-end items-center mb-4">
        <button wire:click="$toggle('showAcademicYearForm')"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Add Year
        </button>
    </div>

    <div class="relative">

        <!-- TABLE -->
        <x-table :headers="['Academic Year', 'Start Date','End Date','Detail' ,'Actions']">
            @forelse($academicYears as $academicYear)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $academicYear->year_name }}</td>
                    <td class="px-4 py-2">{{ $academicYear->start_date }}</td>
                    <td class="px-4 py-2">{{ $academicYear->end_date }}</td>

                    <td class="px-4 py-2">
                        <button wire:click="viewAcademicYear({{ $academicYear->year_id }})"
                            class="bg-gray-500 text-white px-2 py-1 rounded">
                            View
                        </button>
                    </td>

                    <td class="px-4 py-2 flex gap-2">
                        <button wire:click="confirmEditAcademicYear({{ $academicYear->year_id }})"
                            class="bg-blue-500 text-white px-2 py-1 rounded">
                            Edit
                        </button>

                        <button wire:click="confirmDeleteAcademicYear({{ $academicYear->year_id }})"
                            class="bg-red-500 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>    
                    <td colspan="5" class="text-center py-4">No academic years found.</td>
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
        {{ $academicYears->links() }}
    </div>

    <!-- Academic Year Detail Modal -->
    @if($showAcademicYearDetail && $selectedAcademicYear)
         <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">

                <h2 class="text-xl font-bold mb-4">Academic Year Detail</h2>
                <p><strong>Academic Year:</strong> {{ $selectedAcademicYear->year_name }}</p>
                <p><strong>Start Date:</strong> {{ $selectedAcademicYear->start_date }}</p>
                <p><strong>End Date:</strong> {{ $selectedAcademicYear->end_date }}</p>
                   <p><strong>Created At:</strong> {{ $selectedAcademicYear->created_at }}</p>
                <p><strong>Updated At:</strong> {{ $selectedAcademicYear->updated_at }}</p>

                <div class="mt-4 text-right">
                    <button wire:click="$set('showAcademicYearDetail', false)" class="bg-gray-500 text-white px-4 py-2 rounded">
                        Close
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm && $academicYearToDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-red-600">Confirm Delete</h2>
                <p class="mb-4">Are you sure you want to delete this academic year? This action cannot be undone.</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="deleteAcademicYear({{ $academicYearToDelete }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Confirmation Modal -->
    @if($showEditConfirm && $academicYearToEdit)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-blue-600">Confirm Edit</h2>
                <p class="mb-4">Are you sure you want to edit this academic year's information?</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="editAcademicYearConfirmed({{ $academicYearToEdit }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Conditionally show the student form -->
    @if($showAcademicYearForm)
        <livewire:academicsetting.academicyearform :academic-year-id="$selectedAcademicYearId" />
    @endif

</div>