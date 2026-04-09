<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use App\Models\Classroom;


class Classroomform extends Component
{
    public $classroom_id;
    public $room_name;
    public $building;
    public $capacity;
    public $isEdit = false;
    public $showModal = true;
    protected $listeners = [
        'edit-classroom' => 'loadClassroom',
        'closeModal' => 'closeModal',
    ];
    protected $rules = [
        'room_name' => 'required|string',
        'building' => 'nullable|string',
        'capacity' => 'required|integer|min:1',
    ];
    public function mount($classroomId = null)
    {
        if ($classroomId) {
            $this->classroom_id = $classroomId;
            $this->loadClassroom($classroomId);
        }
    }
    public function loadClassroom($id)
    {
        $classroom = Classroom::find($id);
        if ($classroom) {
            $this->classroom_id = $classroom->classroom_id;
            $this->room_name = $classroom->room_name;
            $this->building = $classroom->building;
            $this->capacity = $classroom->capacity;
            $this->isEdit = true;
            $this->showModal = true;
        }
    }
    public function submit()
    {
        $this->validate();

        // Check for duplicate classroom
        $existing = Classroom::where('room_name', $this->room_name)
            ->where('building', $this->building);

        if ($this->isEdit) {
            $existing = $existing->where('classroom_id', '!=', $this->classroom_id);
        }

        $existing = $existing->first();

        if ($existing) {
            $this->addError('duplicate', 'A classroom with the same room name and building already exists.');
            return;
        }

        if ($this->isEdit) {
            $classroom = Classroom::find($this->classroom_id);
            if ($classroom) {
                $classroom->update([
                    'room_name' => $this->room_name,
                    'building' => $this->building,
                    'capacity' => $this->capacity,
                ]);
                $message = 'Classroom updated successfully';
            }
        } else {
            Classroom::create([
                'room_name' => $this->room_name,
                'building' => $this->building,
                'capacity' => $this->capacity,
            ]);
            $message = 'Classroom created successfully';
        }      
        $this->resetForm();
        $this->dispatch('closeModal');
        $this->dispatch('classroom-created', ['message' => $message]);
  
        
    }
    public function resetForm()
    {        
        $this->isEdit = false;
        $this->classroom_id = null;
        $this->room_name = null;
        $this->building = null;
        $this->capacity = null;

    }
    public function closeModal()
    {   
        $this->resetForm();
        $this->showModal = false;
    }


    public function render()
    {
        return view('livewire.classroom.classroomform');
    }
}
