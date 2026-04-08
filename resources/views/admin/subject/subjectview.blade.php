<x-layouts.app title="Subjects" :showLoader="false">
    <livewire:layout.admin-layout>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Subject Details</h2>
        </div>
        <livewire:subject.subjectview />
    </livewire:layout.admin-layout>
</x-layouts.app>