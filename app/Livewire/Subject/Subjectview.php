<?php

namespace App\Livewire\Subject;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Subject;

class Subjectview extends Component
{
    use WithPagination;

    public $query = '';
    public $selectedSubject = null;
    public $selectedSubjectId = null;
    public $showSubjectDetail = false;
    public $showSubjectForm = false;
    public $showDeleteConfirm = false;
    public $subjectToDelete = null;
    public $showEditConfirm = false;
    public $subjectToEdit = null;

    protected $paginationTheme = 'tailwind';

    protected $listeners = [
        'subjectCreated' => 'handleSubjectCreated',
        'closeModal' => 'handleCloseModal',
    ];

    /**
     * Handle refresh event from model observer
     */
    #[On('refresh-subjects')]
    public function refreshSubjects($data = [])
    {
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function handleSubjectCreated($message)
    {
        $this->showSubjectForm = false;
        $this->selectedSubjectId = null;
        $this->resetPage();
    }

    public function handleCloseModal()
    {
        $this->showSubjectForm = false;
        $this->selectedSubjectId = null;
    }

    public function openModal($subjectId = null)
    {
        $this->selectedSubjectId = $subjectId;
        $this->showSubjectForm = true;
        if ($subjectId) {
            $this->dispatch('subject.subjectform', 'edit-subject', $subjectId);
        }
    }

    public function viewSubject($id)
    {
        $this->selectedSubject = Subject::find($id);
        $this->showSubjectDetail = true;
    }

    public function confirmEditSubject($id)
    {
        $this->subjectToEdit = $id;
        $this->showEditConfirm = true;
    }

    public function editSubjectConfirmed($id)
    {
        $this->selectedSubjectId = $id;
        $this->showSubjectForm = true;
        $this->dispatch('subject.subjectform', 'edit-subject', $id);
        $this->showEditConfirm = false;
        $this->subjectToEdit = null;
    }

    public function confirmDeleteSubject($id)
    {
        $this->subjectToDelete = $id;
        $this->showDeleteConfirm = true;
    }

    public function deleteSubject($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $subject->update(['status' => 'I']);
        }
        $this->dispatch('subject-created', ['message' => 'Subject deleted successfully']);
        $this->showDeleteConfirm = false;
        $this->subjectToDelete = null;
        $this->resetPage();
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->subjectToDelete = null;
    }


    public function render()
    {
        $subjects = Subject::where('status', 'A')
            ->when($this->query, function ($q) {
                $q->where('subject_name', 'like', "%{$this->query}%")
                    ->orWhere('subject_id', 'like', "%{$this->query}%")
                    ->orWhere('description', 'like', "%{$this->query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.subject.subjectview', [
            'subjects' => $subjects,
        ]);
    }
}
