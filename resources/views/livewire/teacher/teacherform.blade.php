<div>
    @if($showModal)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            wire:click="$dispatch('closeModal')" id="my-modal">
            <div class="relative top-5 mx-auto p-5 border md:w-1/2 w-96 shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="mt-3 ">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Teacher Form</h3>
                    <div class="mt-2 px-7 py-3">
                        <div class="grid gap-6 mb-6">
                            <form wire:submit.prevent="submit">
                                @error('duplicate')
                                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <x-form.input-text label="Teacher Code" name="teacher_code" type="text"
                                    :value="$teacher_id" disabled />
                                    <!-- English Name Group -->
                                    <div class="mb-4">
                                        <h3 class="font-semibold text-gray-700 mb-2">English Name</h3>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                                            <x-form.input-text label="First Name" name="en_first_name" :value="$en_first_name" />
                                            <x-form.input-text label="Last Name" name="en_last_name" :value="$en_last_name" />
                                        </div>
                                    </div>

                                    <!-- Khmer Name Group -->
                                    <div class="mb-4">
                                        <h3 class="font-semibold text-gray-700 mb-2">Khmer Name</h3>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                                            <x-form.input-text label="First Name" name="kh_first_name" :value="$kh_first_name" />
                                            <x-form.input-text label="Last Name" name="kh_last_name" :value="$kh_last_name" />
                                        </div>
                                    </div>
                                <x-form.input-radio label="Gender" name="gender" :options="['M' => 'Male', 'F' => 'Female']" />
                                <x-form.input-text label="Date of Birth" name="dateofbirth" type="date"
                                    :value="$dateofbirth" />
                                <x-form.input-textarea label="Place of Birth" name="placeofbirth" :value="$placeofbirth" />
                                <x-form.input-textarea label="Address" name="address" :value="$address" />
                                <x-form.input-text label="Phone" name="phone" :value="$phone" />
                                <x-form.input-text label="Email" name="email" type="email" :value="$email" />
                                <x-form.file-upload label="Profile Photo" name="photo" />
                                @if ($photo)
                                    <div class="mt-2 relative">
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-24 h-24 rounded object-cover">

                                        <button type="button" wire:click="removePhoto"
                                            class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                            ✕
                                        </button>
                                    </div>
                                @endif

                                <div class="flex justify-end space-x-2 mt-4">
                                    <x-form.button type="submit" color="green" wire:loading.attr="disabled"
                                        wire:target="photo,submit">
                                        <span wire:loading.remove>
                                            {{ $isEdit ? 'Update' : 'Save' }}
                                        </span>
                                        <span wire:loading class="flex items-center gap-2">
                                            @if($isSaving)
                                                {{ $isEdit ? 'Updating...' : 'Saving...' }}
                                            @else
                                                {{ $isEdit ? 'Update' : 'Save' }}
                                            @endif
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