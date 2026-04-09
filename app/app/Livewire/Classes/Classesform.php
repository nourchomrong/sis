<?php

namespace App\Livewire\Classes;

use Livewire\Component;
use App\Models\Classes;
use App\Models\Year;
use App\Models\Classroom;
use App\Models\Teacher;

class Classesform extends Component
{
    public $class_id;
    public $class_name;
    public $grade_level;
    public $year_id;
    public $classroom_id;
    public $teacher_id;
    public $isEdit = false;
    public $showModal = true;
    
    public $yearOptions = [];
    public $classroomOptions = [];
    public $instructorOptions = [];
    protected $listeners = [
        'edit-classes' => 'loadClasses',
        'closeModal' => 'closeModal',
    ];
    protected $rules = [
        'class_name' => 'required|string',
        'grade_level' => 'required|string',
        'year_id' => 'required|exists:years,year_id',
        'classroom_id' => 'required|exists:classrooms,classroom_id',
        'teacher_id' => 'nullable|exists:teachers,teacher_id',
    ];
    
    public function mount($classesId = null)
    {
        $this->loadOptions();
        if ($classesId) {
            $this->class_id = $classesId;
            $this->loadClasses($classesId);
        }
    }
    
    public function loadOptions()
    {
        $this->yearOptions = Year::where('status', 'A')
            ->pluck('year_name', 'year_id')
            ->toArray();
        
        $this->classroomOptions = Classroom::where('status', 'A')
            ->pluck('room_name', 'classroom_id')
            ->toArray();
        
        $this->instructorOptions = Teacher::where('status', 'A')
            ->pluck('en_fullname', 'teacher_id')
            ->toArray();
    }
    public function loadClasses($id)
    {
        $classes = Classes::find($id);
        if ($classes) {
            $this->class_id = $classes->class_id;
            $this->class_name = $classes->class_name;
            $this->grade_level = $classes->grade_level;
            $this->year_id = $classes->year_id;
            $this->classroom_id = $classes->classroom_id;
            $this->teacher_id = $classes->teacher_id;
            $this->isEdit = true;
            $this->showModal = true;
        }
    }
    public function submit()
    {
        $this->validate();
        // Check for duplicate class
        $existing = Classes::where('class_name', $this->class_name)
            ->where('grade_level', $this->grade_level)
            ->where('year_id', $this->year_id);
        if ($this->isEdit) {
            $existing = $existing->where('class_id', '!=', $this->class_id);
        }
        $existing = $existing->first();
        if ($existing) {
            $this->addError('duplicate', 'A class with the same name, grade level, and year already exists.');
            return;
        }
        if ($this->isEdit) {
            $classes = Classes::find($this->class_id);
            if ($classes) {
                $classes->update([
                    'class_name' => $this->class_name,
                    'grade_level' => $this->grade_level,
                    'year_id' => $this->year_id,
                    'classroom_id' => $this->classroom_id,
                    'teacher_id' => $this->teacher_id,
                ]);
                $message = 'Class updated successfully.';
     
       }
        } else {
            Classes::create([
                'class_name' => $this->class_name,
                'grade_level' => $this->grade_level,
                'year_id' => $this->year_id,
                'classroom_id' => $this->classroom_id,
                'teacher_id' => $this->teacher_id,
            ]);
            $message = 'Class created successfully.';
        }
        $this->resetForm();
        $this->dispatch('closeModal');
        $this->dispatch('classes-created', ['message' => $message]);
    }
    private function resetForm()
    {        $this->class_id = null;
        $this->class_name = '';
        $this->grade_level = '';
        $this->year_id = null;
        $this->classroom_id = null;
        $this->teacher_id = null;
        $this->isEdit = false;
    }
    public function closeModal()
    {        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.classes.classesform', [
            'yearOptions' => $this->yearOptions,
            'classroomOptions' => $this->classroomOptions,
            'instructorOptions' => $this->instructorOptions,
        ]);
    }
}
