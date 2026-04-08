<?php

namespace App\Livewire\Subject;

use Livewire\Component;
use App\Models\Subject;

class Subjectform extends Component
{
    public $isSaving = false;
    public $showModal = true;
    public $subject_id = null;
    public $subject_code;
    public $subject_name;
    public $description;
    public $isEdit = false;

    protected $listeners = [
        'edit-subject' => 'loadSubject',
        'closeModal' => 'closeModal',
    ];

    protected $rules = [
        'subject_code' => 'nullable|string|max:50',
        'subject_name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    public function mount($subjectId = null)
    {
        if ($subjectId) {
            $this->loadSubject($subjectId);
        }
    }

    public function loadSubject($data)
    {
        $subject_id = is_array($data) ? ($data['subject_id'] ?? null) : $data;
        if (! $subject_id) {
            return;
        }

        $subject = Subject::find($subject_id);
        if (! $subject) {
            return;
        }

        $this->subject_id = $subject->subject_id;
        $this->subject_code = $subject->subject_code;
        $this->subject_name = $subject->subject_name;
        $this->description = $subject->description;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function submit()
    {
        $this->isSaving = true;
        $this->validate();

        $existing = Subject::where('subject_name', $this->subject_name);
        if ($this->isEdit) {
            $existing = $existing->where('subject_id', '!=', $this->subject_id);
        }

        if ($existing->exists()) {
            $this->addError('duplicate', 'A subject with this name already exists.');
            $this->isSaving = false;
            return;
        }

        if ($this->isEdit) {
            $subject = Subject::find($this->subject_id);
            if ($subject) {
                $subject->update([
                    'subject_name' => $this->subject_name,
                    'description' => $this->description,
                ]);
            }
            $message = 'Subject updated successfully.';
        } else {
            $subject = Subject::create([
                'subject_code' => $this->subject_code ?: 'temp-' . uniqid(),
                'subject_name' => $this->subject_name,
                'description' => $this->description,
                'status' => 'A',
            ]);

            if ($subject) {
                $subject->update([
                    'subject_code' => '0000' . $subject->subject_id,
                ]);
            }

            $message = 'Subject created successfully.';
        }

        $this->dispatch('subject-created', ['message' => $message]);
        $this->dispatch('subjectCreated', $message);
        $this->dispatch('closeModal');
        $this->resetForm();
        $this->isSaving = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->subject_id = null;
        $this->subject_code = null;
        $this->subject_name = null;
        $this->description = null;
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.subject.subjectform');
    }
}
