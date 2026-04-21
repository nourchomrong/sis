<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use App\Models\Classroom;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Classroomview extends Component
{
    use WithPagination;
    public $query = '';
    public $selectedClassroom = null;
    public $selectedClassroomId = null;
    public $showClassroomDetail = false;
    public $showClassroomForm = false;
    public $showDeleteConfirm = false;
    public $classroomToDelete = null;
    public $showEditConfirm = false;
    public $classroomToEdit = null;
    protected $paginationTheme = 'tailwind'; // or tailwind

    protected $listeners = [
        'classroomCreated' => 'handleClassroomCreated',
        'closeModal' => 'handleCloseModal',
    ];

    /**
     * Handle refresh event from model observer
     */
    #[On('refresh-classrooms')]
    public function refreshClassrooms($data = [])
    {
        $this->resetPage();
    }

    public function viewClassroom($id)
    {
        $this->selectedClassroom = Classroom::find($id);
        $this->showClassroomDetail = true;
    }
    public function getClassroomsProperty()
    {
        return Classroom::query()
            ->where('status', 'A')
            ->when($this->query, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('room_name', 'like', "%{$this->query}%")
                        ->orWhere('building', 'like', "%{$this->query}%")
                        ->orWhere('capacity', 'like', "%{$this->query}%");
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
        $this->showClassroomForm = false;
        $this->selectedClassroomId = null;
    }

    public function editClassroom($id)
    {
        $this->selectedClassroomId = $id;
        $this->showClassroomForm = true;
        $this->dispatch('classroom-created', 'message' , 'edit-classroom', $id);
    }

    public function confirmEditClassroom($id)
    {
        $this->classroomToEdit = $id;
        $this->showEditConfirm = true;
    }

    public function editClassroomConfirmed($id)
    {
        $this->selectedClassroomId = $id;
        $this->showClassroomForm = true;
        $this->dispatch('classroom.classroomform', 'edit-classroom', $id);
        $this->showEditConfirm = false;
        $this->classroomToEdit = null;
    }

    public function cancelEdit()
    {
        $this->showEditConfirm = false;
        $this->classroomToEdit = null;
    }

    public function confirmDeleteClassroom($id)
    {
        $this->classroomToDelete = $id;
        $this->showDeleteConfirm = true;
    }

    public function deleteClassroom($id)
    {
        $classroom = Classroom::find($id);
        if ($classroom) {
            $classroom->update(['status' => 'I']); // soft delete
        }
        $this->dispatch('classroom-created', ['message' => 'Classroom deleted successfully']);
        $this->showDeleteConfirm = false;
        $this->classroomToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->classroomToDelete = null;
    }
    public function render()
    {
        return view('livewire.classroom.classroomview', [
            'classrooms' => $this->classrooms
        ]);
    }
}
