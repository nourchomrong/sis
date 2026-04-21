<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Student;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Studentview extends Component
{
  use WithPagination;

    public $query = '';
    public $selectedStudent = null;
    public $selectedStudentId = null;
    public $showStudentDetail = false;
    public $showStudentForm = false;
    public $showDeleteConfirm = false;
    public $studentToDelete = null;
    public $showEditConfirm = false;
    public $studentToEdit = null;

    protected $paginationTheme = 'tailwind'; // or tailwind

    // Listen for dispatched events from child
    protected $listeners = [
        'studentCreated' => 'handleStudentCreated',
        'closeModal' => 'handleCloseModal',
    ];

    /**
     * Handle refresh event from model observer or polling
     */
    #[On('refresh-students')]
    public function refreshStudents($data = [])
    {
        // Reset to first page and refresh the data
        $this->resetPage();
    }

        public function viewStudent($id)
        {
            $this->selectedStudent = Student::find($id);
            $this->showStudentDetail = true;
        }
    public function getStudentsProperty()
    {
        return Student::query()
            ->where('status', 'A')
            ->when($this->query, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('en_fullname', 'like', "%{$this->query}%")
                        ->orWhere('kh_fullname', 'like', "%{$this->query}%")
                        ->orWhere('student_id', 'like', "%{$this->query}%")
                        ->orWhere('phone', 'like', "%{$this->query}%");
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
        $this->showStudentForm = false;
        $this->selectedStudentId = null;
        
        // Refresh data when modal closes
        $this->refreshStudents();
    }




    public function confirmEditStudent($id)
    {
        $this->studentToEdit = $id;
        $this->showEditConfirm = true;
    }

    public function editStudent($id)
    {
        $this->selectedStudentId = $id;
        $this->showStudentForm = true;
        $this->dispatch('student.studentform', 'edit-student', $id);
        $this->showEditConfirm = false;
        $this->studentToEdit = null;
    }

    public function cancelEdit()
    {
        $this->showEditConfirm = false;
        $this->studentToEdit = null;
    }

    public function confirmDeleteStudent($id)
    {
        $this->studentToDelete = $id;
        $this->showDeleteConfirm = true;
    }

    public function deleteStudent($id)
    {
        $student = Student::find($id);
        if ($student) {
            $student->update(['status' => 'I']); // soft delete
        }
        $this->dispatch('student-created', ['message' => 'Student deleted successfully']);
        $this->showDeleteConfirm = false;
        $this->studentToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->studentToDelete = null;
    }


   public function render()
    {
        return view('livewire.student.studentview', [
            'students' => $this->students
        ]);
    }
}

