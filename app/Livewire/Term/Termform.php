<?php

namespace App\Livewire\Term;

use Livewire\Component;
use App\Models\Term;
use Carbon\Carbon;

class Termform extends Component
{
    public $term_id;
    public $name;
    public $type;
    public $start_date;
    public $end_date;
    public $isEdit = false;
    public $showModal = true;

    protected $listeners = [
        'edit-term' => 'loadTerm',
        'closeModal' => 'closeModal',
    ];

    protected $rules = [
        'name' => 'required|string',
        'type' => 'nullable|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function mount($termId = null)
    {
        if ($termId) {
            $this->term_id = $termId;
            $this->loadTerm($termId);
        }
    }

   public function loadTerm($id)
{
    $term = Term::find($id);

    if ($term) {
        $this->term_id = $term->term_id;
        $this->name = $term->name; 
        $this->type = $term->type;
        $this->start_date = $term->start_date;
        $this->end_date = $term->end_date;
        $this->isEdit = true;
    }
}

   public function submit()
{
    $this->validate();

    // Check duplicate ONLY by name
    $existing = Term::where('name', $this->name);

    if ($this->isEdit) {
        $existing->where('term_id', '!=', $this->term_id);
    }

    if ($existing->first()) {
        $this->addError('duplicate', 'A term with the same name already exists.');
        return;
    }

    if ($this->isEdit) {
        $term = Term::find($this->term_id);

        if ($term) {
            $term->update([
                'name' => $this->name, // ✅ clean
                'type' => $this->type,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);
            $message = 'Term updated successfully.';
        }
    } else {
        Term::create([
            'name' => $this->name, // ✅ clean
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
        $message = 'Term created successfully.';
    }

    $this->resetForm();

    $this->dispatch('closeModal');
    $this->dispatch('term-created', ['message' => $message]);
}

    // ✅ Live preview
    public function getPreviewNameProperty()
    {
        if (!$this->name) return '';

        if (!$this->start_date || !$this->end_date) {
            return $this->name;
        }

        return $this->formatName();
    }

    public function resetForm()
    {
        $this->term_id = null;
        $this->name = '';
        $this->type = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->isEdit = false;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.term.termform');
    }
}