<div>
   <!-- Real-time polling script -->
    <script>
        (function() {
            // Poll for changes every 3 seconds
            setInterval(() => {
                // This triggers Livewire to refresh the component
                if (window.Livewire && Livewire.find) {
                    Livewire.dispatch('refresh-terms');
                }
            }, 3000);
        })();
    </script>
   <!-- Toast -->
   <div
        x-data="{ show: false, message: '', timeout: null }"
        x-cloak
        x-init="
            window.addEventListener('term-created', event => {
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

   <x-form.search label="Search Term" name="query" placeholder="Type to search..." />

    <!-- Header + Add button -->
    <div class="flex justify-end items-center mb-4">
        <button wire:click="$toggle('showTermForm')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Add Terms
        </button>
    </div>

    <div class="relative">

        <!-- Term Table -->
        <x-table :headers="['Term Name','Term Type','Start Date','End Date','Detail','Actions']">

            @forelse($terms as $term)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $term->name }} ({{ \Carbon\Carbon::parse($term->start_date ?? 'N/a')->format('M j, Y') }} - {{ \Carbon\Carbon::parse($term->end_date ?? 'N/a')->format('M j, Y') }})</td>
                    <td class="px-4 py-2">{{ $term->type }}</td>
                    <td class="px-4 py-2">{{ $term->start_date }}</td>
                    <td class="px-4 py-2">{{ $term->end_date }}</td>

                    <td class="px-4 py-2">
                        <button wire:click="viewTerm({{ $term->term_id }})" class="bg-gray-500 text-white px-2 py-1 rounded">
                            View
                        </button>
                    </td>

                    <td class="px-4 py-2 flex gap-2">
                        <button wire:click="confirmEditTerm({{ $term->term_id }})" class="bg-blue-500 text-white px-2 py-1 rounded">
                            Edit
                        </button>
                        <button wire:click="confirmDeleteTerm({{ $term->term_id }})" class="bg-red-500 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center px-4 py-6">No terms found.</td>
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
        {{ $terms->links() }}
    </div>

     @if($showTermDetail && $selectedTerm)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">

                    <h2 class="text-xl font-bold mb-4">Term Detail</h2>
                    <p><strong>Term Name:</strong> {{ $selectedTerm->name }} ({{ \Carbon\Carbon::parse($selectedTerm->start_date ?? 'N/a')->format('M j, Y') }} - {{ \Carbon\Carbon::parse($selectedTerm->end_date ?? 'N/a')->format('M j, Y') }})</p>
                    <p><strong>Term Type:</strong> {{ $selectedTerm->type ?? 'N/A' }}</p>
                    <p><strong>Start Date:</strong> {{ $selectedTerm->start_date }}</p>
                    <p><strong>End Date:</strong> {{ $selectedTerm->end_date }}</p>

                    <div class="mt-4 text-right">
                        <button wire:click="$set('showTermDetail', false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm && $termToDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-red-600">Confirm Delete</h2>
                <p class="mb-4">Are you sure you want to delete this term? This action cannot be undone.</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="deleteTerm({{ $termToDelete }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Confirmation Modal -->
    @if($showEditConfirm && $termToEdit)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-blue-600">Confirm Edit</h2>
                <p class="mb-4">Are you sure you want to edit this term's information?</p>
                <div class="flex justify-end space-x-2">
                    <button wire:click="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </button>
                    <button wire:click="editTermConfirm({{ $termToEdit->term_id }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Edit
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Conditionally show the term form -->
    @if($showTermForm)
        <livewire:term.termform :term-id="$selectedTermId" />
    @endif
</div>
