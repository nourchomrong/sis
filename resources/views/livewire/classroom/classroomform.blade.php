<div>
    @if($showModal)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            wire:click="$dispatch('closeModal')" id="my-modal">
            <div class="relative top-5 mx-auto p-5 border md:w-1/2 w-96 shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="mt-3 ">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Classroom Form</h3>
                    <div class="mt-2 px-7 py-3">
                        <div class="grid gap-6 mb-6">
                            <form wire:submit.prevent="submit">
                                @error('duplicate')
                                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <x-form.input-text label="Room Name" name="room_name" :value="$room_name" />
                                <x-form.input-text label="Building" name="building" :value="$building" />
                                <x-form.input-text label="Capacity" name="capacity" type="number" :value="$capacity" />

                                <div class="flex justify-end space-x-2 mt-4">
                                    <x-form.button type="submit" color="green" wire:loading.attr="disabled" wire:target="submit">
                                        <span wire:loading.remove>
                                            {{ $isEdit ? 'Update' : 'Save' }}
                                        </span>
                                        <span wire:loading>
                                            {{ $isEdit ? 'Updating...' : 'Saving...' }}
                                        </span>
                                    </x-form.button>
                                    <x-form.button type="button" color="red" wire:click="$dispatch('closeModal')">
                                        Cancel
                                    </x-form.button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

</div>
