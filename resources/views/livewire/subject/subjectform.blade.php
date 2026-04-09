<div>
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ $isEdit ? 'Edit Subject' : 'Add Subject' }}
                    </h2>
                    <button wire:click="$emitUp('closeModal')" class="text-gray-500 hover:text-gray-700">✕</button>
                </div>
                <form wire:submit.prevent="submit" class="px-6 py-6 space-y-4">
                    @error('duplicate')
                        <div class="rounded bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                            {{ $message }}
                        </div>
                    @enderror

                    <x-form.input-text label="Subject Code" name="subject_code" :value="$subject_code" disabled placeholder="Auto-generated after save" />
                    <x-form.input-text label="Subject Name" name="subject_name" :value="$subject_name" />
                    <x-form.input-textarea label="Description" name="description" :value="$description" />

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" wire:click="$emitUp('closeModal')"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                            <span wire:loading.remove>{{ $isEdit ? 'Update' : 'Save' }}</span>
                            <span wire:loading>{{ $isEdit ? 'Updating...' : 'Saving...' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
