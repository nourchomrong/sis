<x-layouts.app title="Schedules" :showLoader="false">
    <livewire:layout.admin-layout>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Schedule Details</h2>
        </div>
        <livewire:schedule.scheduleview />
    </livewire:layout.admin-layout>
</x-layouts.app>