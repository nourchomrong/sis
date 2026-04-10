<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Teacher;

class Teacherview extends Component
{
    use WithPagination;

    public $query = '';
    public $selectedTeacher = null;
    public $selectedTeacherId = null;
    public $showTeacherDetail = false;
    public $showTeacherForm = false;
    public $message;
    public $showModal = false;
    public $showDeleteConfirm = false;
    public $teacherToDelete = null;
    public $showEditConfirm = false;
    public $teacherToEdit = null;

    // Listen for dispatched events from child
    protected $listeners = [
        'teacherCreated' => 'handleTeacherCreated',
        'closeModal' => 'handleCloseModal',
    ];

    protected $paginationTheme = 'tailwind';
    protected $queryString = [
        'query' => ['except' => ''],
    ];

    public function handleTeacherCreated($msg)
    {
        $this->message = $msg;
        $this->resetPage(); // Reset to first page after creation
    }

    public function viewTeacher($id)
    {
        $this->selectedTeacher = Teacher::find($id);
        $this->showTeacherDetail = true;
    }

    public function handleCloseModal()
    {
        $this->showTeacherForm = false;
        $this->selectedTeacherId = null;
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

public function getTeachersProperty()
{
    return Teacher::where('status', 'A')
        ->when($this->query, function ($q) {
            $q->where(function ($sub) {
                $sub->where('en_fullname', 'like', "%{$this->query}%")
                    ->orWhere('kh_fullname', 'like', "%{$this->query}%")
                    ->orWhere('teacher_id', 'like', "%{$this->query}%")
                    ->orWhere('phone', 'like', "%{$this->query}%");
            });
        })
        ->orderBy('created_at', 'desc') // ← NEWEST FIRST
        ->paginate(10);
}

    public function editTeacher($id)
    {
        $this->selectedTeacherId = $id;
        $this->showTeacherForm = true;
        $this->dispatch('teacher.teacherform', 'edit-teacher', $id);
    }

    public function confirmEditTeacher($id)
    {
        $this->teacherToEdit = $id;
        $this->showEditConfirm = true;
    }

    public function editTeacherConfirmed($id)
    {
        $this->selectedTeacherId = $id;
        $this->showTeacherForm = true;
        $this->dispatch('teacher.teacherform', 'edit-teacher', $id);
        $this->showEditConfirm = false;
        $this->teacherToEdit = null;
    }

    public function cancelEdit()
    {
        $this->showEditConfirm = false;
        $this->teacherToEdit = null;
    }

    public function confirmDeleteTeacher($id)
    {
        $this->teacherToDelete = $id;
        $this->showDeleteConfirm = true;
    }

    public function deleteTeacher($id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            $teacher->update([
                'status' => 'I'
            ]);
        }
        $this->dispatch('teacher-created', ['message' => 'Teacher deleted successfully']);
        $this->resetPage(); // Reset to first page after deletion
        $this->showDeleteConfirm = false;
        $this->teacherToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->teacherToDelete = null;
    }

    public function openModal($teacherId = null)
    {
        $this->selectedTeacherId = $teacherId;
        $this->showTeacherForm = true;
    }

    public function render()
    {
        return view('livewire.teacher.teacherview', [
            'teachers' => $this->teachers,
        ]);
    }
}
