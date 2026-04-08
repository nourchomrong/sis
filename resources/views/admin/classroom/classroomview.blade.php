<x-layouts.app title="Classroom" :showLoader="false">
    <livewire:layout.admin-layout>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Classroom Details</h2>
        </div>
        <livewire:classroom.classroomview />
    </livewire:layout.admin-layout>
</x-layouts.app>