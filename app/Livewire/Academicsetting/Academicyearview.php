<?php

namespace App\Livewire\Academicsetting;

use Livewire\Component;
use App\Models\Year;
use Livewire\WithPagination;
class Academicyearview extends Component
{
    use WithPagination;
    public $query = '';
    public $selectedAcademicYear = null;
    public $selectedAcademicYearId = null;
    public $showAcademicYearDetail = false;
    public $showAcademicYearForm = false;
    public $showDeleteConfirm = false;
    public $academicYearToDelete = null;
    public $showEditConfirm = false;
    public $academicYearToEdit = null;
    protected $paginationTheme = 'tailwind'; // or tailwind
    
    protected $listeners = [
        'academicyear-created' => 'handleAcademicYearCreated',
        'closeModal' => 'handleCloseModal',
    ];

    public function viewAcademicYear($id)
    {
        $this->selectedAcademicYear = Year::find($id);
        $this->showAcademicYearDetail = true;
    }
    public function getAcademicYearsProperty()
    {
        return Year::query()
            ->where('status', 'A')
            ->when($this->query, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('year_name', 'like', "%{$this->query}%")
                        ->orWhere('start_date', 'like', "%{$this->query}%")
                        ->orWhere('end_date', 'like', "%{$this->query}%");
                });
            })
            ->orderBy('created_at', 'desc') // ← NEWEST FIRST
            ->paginate(10);
    }
    public function updatedQuery()
    {
        $this->resetPage();
    }
    public function handleCloseModal()
    {
        $this->showAcademicYearForm = false;
        $this->selectedAcademicYearId = null;
    }

    public function handleAcademicYearCreated($payload)
    {
        $this->resetPage();
    }

    public function editAcademicYear($id)
    {
        $this->selectedAcademicYearId = $id;
        $this->showAcademicYearForm = true;
        $this->dispatch('academicsetting.academicyearform', 'edit-academicyear', $id);
    }

    public function confirmEditAcademicYear($id)
    {
        $this->academicYearToEdit = $id;
        $this->showEditConfirm = true;
    }
    public function editAcademicYearConfirmed($id)
    {
        $this->selectedAcademicYearId = $id;
        $this->showAcademicYearForm = true;
        $this->dispatch('academicsetting.academicyearform', 'edit-academicyear', $id);
        $this->showEditConfirm = false;
        $this->academicYearToEdit = null;
    }
    public function cancelEdit()
    {
        $this->showEditConfirm = false;
        $this->academicYearToEdit = null;
    }
    public function confirmDeleteAcademicYear($id)
    {
        $this->academicYearToDelete = $id;
        $this->showDeleteConfirm = true;
    }
    public function deleteAcademicYear($id)
    {
        $academicYear = Year::find($id);
        if ($academicYear) {
            $academicYear->update(['status' => 'I']);
        }
        $this->showDeleteConfirm = false;
        $this->academicYearToDelete = null;
    }
    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->academicYearToDelete = null;
    }
    public function render()
    {
        return view('livewire.academicsetting.academicyearview', [
            'academicYears' => $this->academicYears
        ]);
    }
}
