<x-layouts.app title="Academic Year" :showLoader="false"> 
    <livewire:layout.admin-layout>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Academic Year Details</h2>
        </div>
        <livewire:academicsetting.academicyearview />
    </livewire:layout.admin-layout>
</x-layouts.app>