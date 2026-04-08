<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">
            {{ $isEdit ? 'Edit Schedule' : 'Create Schedule' }}
        </h2>
        <button wire:click="closeModal" type="button"
            class="text-gray-400 hover:text-gray-600">
            ✕
        </button>
    </div>

    <form wire:submit="submit" class="space-y-4">
        <!-- Class Selection -->
        <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">
                Class <span class="text-red-500">*</span>
            </label>
            <select wire:model="class_id" id="class_id"
                @if($disableClassSelection) disabled @endif
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Class --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->class_id }}">
                        {{ $class->class_name }} (Grade {{ $class->grade_level }})
                    </option>
                @endforeach
            </select>
            @if($disableClassSelection)
                <input type="hidden" wire:model="class_id" />
                <p class="text-xs text-gray-500 mt-1">Class is fixed when adding schedule from a class.</p>
            @endif
            @error('class_id')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Subject Selection -->
        <div>
            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">
                Subject <span class="text-red-500">*</span>
            </label>
            <select wire:model="subject_id" id="subject_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Subject --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->subject_id }}">
                        {{ $subject->subject_name }} ({{ $subject->subject_code }})
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Teacher Selection -->
        <div>
            <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">
                Teacher <span class="text-gray-500">(Optional)</span>
            </label>
            <select wire:model="teacher_id" id="teacher_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Teacher --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->teacher_id }}">
                        {{ $teacher->en_fullname }}
                    </option>
                @endforeach
            </select>
            @error('teacher_id')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Day of Week -->
        <div>
            <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">
                Day of Week <span class="text-red-500">*</span>
            </label>
            <select wire:model="day_of_week" id="day_of_week"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Day --</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
            @error('day_of_week')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Start Time -->
        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                Start Time <span class="text-red-500">*</span>
            </label>
            <input type="time" wire:model="start_time" id="start_time"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            @error('start_time')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- End Time -->
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                End Time <span class="text-red-500">*</span>
            </label>
            <input type="time" wire:model="end_time" id="end_time"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            @error('end_time')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Error Message -->
        @if($errors->has('submit'))
            <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ $errors->first('submit') }}
            </div>
        @endif

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <button type="button" wire:click="closeModal"
                class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50">
                {{ $isSaving ? 'Saving...' : ($isEdit ? 'Update' : 'Create') }}
            </button>
        </div>
    </form>
</div>
