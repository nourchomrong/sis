<?php

namespace App\Livewire\Term;

use App\Models\Term;
use Livewire\WithPagination;

use Livewire\Component;

class Termview extends Component
{
    use WithPagination;
    public $query = '';
    public $selectedTerm = null;
    public $selectedTermId = null;
    public $showTermDetail = false;
    public $showTermForm = false;
    public $message;
    public $showModal = false;
    public $showDeleteConfirm = false;
    public $termToDelete = null;
    public $showEditConfirm = false;
    public $termToEdit = null;
    // Listen for dispatched events from child
    protected $listeners = [
        'termCreated' => 'handleTermCreated',
        'closeModal' => 'handleCloseModal',
    ];
    protected $paginationTheme = 'tailwind';
    protected $queryString = [
        'query' => ['except' => ''],
    ];
    public function handleTermCreated($msg)
    {
        $this->message = $msg;
        $this->resetPage(); // Reset to first page after creation
    }
    public function viewTerm($id)
    {
        $this->selectedTerm = Term::find($id);
        $this->showTermDetail = true;
    }
    public function handleCloseModal()
    {        
        $this->showTermForm = false;
        $this->selectedTermId = null;
    }
    public function updatedQuery()
    {        
        $this->resetPage();
    }
    public function getTermsProperty()
    {
        return Term::where('status', 'A')
            ->when($this->query, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->query}%")
                        ->orWhere('type', 'like', "%{$this->query}%")
                        ->orWhere('start_date', 'like', "%{$this->query}%")
                        ->orWhere('end_date', 'like', "%{$this->query}%");
                });
            })
            ->orderBy('created_at', 'desc') // ← NEWEST FIRST
            ->paginate(10);
    }
    public function editTerm($id)
    {
        $this->selectedTermId = $id;
        $this->showTermForm = true;
        $this->dispatch('term.termform', 'edit-term', $id);
    }
    public function confirmEditTerm($id)
    {
        $this->termToEdit = Term::find($id);
        $this->showEditConfirm = true;
    }
    public function editTermConfirm($id)
    {
        $this->selectedTermId = $id;
        $this->showTermForm = true;
        $this->showEditConfirm = false; 
        $this->dispatch('term.termform', 'edit-term', $id);
        $this->termToEdit = null;
    }
    public function cancelEdit()
    {
        $this->showEditConfirm = false;
        $this->termToEdit = null;
    }
    public function confirmDeleteTerm($id)
    {
        $this->termToDelete = Term::find($id);
        $this->showDeleteConfirm = true;
    }
    public function deleteTerm($id)
    {
        $term = Term::find($id);
        if ($term) {
            $term->update(['status' => 'I']);
        }
        $this->dispatch('term-created', ['Term deleted successfully.']);
        $this->resetPage(); // Reset to first page after deletion
        $this->showDeleteConfirm = false;
    }
    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->termToDelete = null;
    }
    public function openModal($termId = null)
    {
        $this->selectedTermId = $termId;
        $this->showTermForm = true;
    }
    public function render()
    {
        return view('livewire.term.termview', [
            'terms' => $this->terms,
        ]);
    }
}
