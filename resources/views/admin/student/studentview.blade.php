<x-layouts.app title="Student" :showLoader="false"> 
    <livewire:layout.admin-layout>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Student Details</h2>
        </div>
        <livewire:student.studentview />
        
    </livewire:layout.admin-layout>
</x-layouts.app>