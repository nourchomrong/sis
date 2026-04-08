<x-layouts.app title="Teacher" :showLoader="false"> 
    <livewire:layout.admin-layout>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Teacher Details</h2>
        </div>
        <livewire:teacher.teacherview />
    </livewire:layout.admin-layout>
</x-layouts.app>