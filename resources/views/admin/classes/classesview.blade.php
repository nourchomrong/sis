<x-layouts.app title="Classes" :showLoader="false"> 
    <livewire:layout.admin-layout>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Classes Details</h2>
        </div>
        <livewire:classes.classesview />
    </livewire:layout.admin-layout>
</x-layouts.app>