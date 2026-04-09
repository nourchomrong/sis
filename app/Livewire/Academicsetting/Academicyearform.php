<?php

namespace App\Livewire\Academicsetting;

use Livewire\Component;
use App\Models\Year;

class Academicyearform extends Component
{
    public $academic_year_id;
    public $year_name;
    public $start_date;
    public $end_date;
    public $isEdit = false;
    public $showModal = true;
    protected $listeners = [
        'edit-academicyear' => 'loadAcademicYear',
        'closeModal' => 'closeModal',
    ];
    protected $rules = [
        'year_name' => 'required|string|unique:years,year_name,' . '$academic_year_id,year_id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];
    public function mount($academicYearId = null)
    {
        if ($academicYearId) {
            $this->academic_year_id = $academicYearId;
            $this->loadAcademicYear($academicYearId);
        }
    }
    
    public function updatedStartDate()
    {
        $this->generateYearName();
    }
    
    public function updatedEndDate()
    {
        $this->generateYearName();
    }
    
    private function generateYearName()
    {
        if ($this->start_date && $this->end_date) {
            $startYear = date('Y', strtotime($this->start_date));
            $endYear = date('Y', strtotime($this->end_date));
            $this->year_name = $startYear . '-' . $endYear;
        }
    }
    public function loadAcademicYear($id)
    {
        $academicYear = Year::find($id);
        if ($academicYear) {
            $this->academic_year_id = $academicYear->year_id;
            $this->start_date = $academicYear->start_date instanceof \Carbon\Carbon
                ? $academicYear->start_date->format('Y-m-d')
                : date('Y-m-d', strtotime($academicYear->start_date));
            $this->end_date = $academicYear->end_date instanceof \Carbon\Carbon
                ? $academicYear->end_date->format('Y-m-d')
                : date('Y-m-d', strtotime($academicYear->end_date));
            $this->generateYearName(); // Generate year_name from dates
            $this->isEdit = true;
            $this->showModal = true;
        }
    }
    public function submit()
    {
        $this->validate();

        $existing = Year::where('year_name', $this->year_name)
            ->where('status', 'A');
        if ($this->isEdit) {
            $existing = $existing->where('year_id', '!=', $this->academic_year_id);
        }
        $existing = $existing->first();

        if ($existing) {
            $this->addError('duplicate', 'An academic year with the same name already exists.');
            return;
        }

        if ($this->isEdit) {
            $academicYear = Year::find($this->academic_year_id);
            if ($academicYear) {
                $academicYear->update([
                    'year_name' => $this->year_name,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                ]);
                $message = 'Academic year updated successfully';
            }
        } else {
            Year::create([
                'year_name' => $this->year_name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'status' => 'A',
            ]);
            $message = 'Academic year created successfully';
        }
        $this->resetForm();
        $this->dispatch('closeModal');
        $this->dispatch('academicyear-created', ['message' => $message]);
    }
    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }
    public function resetForm()
    {        
        $this->academic_year_id = null;
        $this->year_name = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->isEdit = false;
    }

    public function render()
    {
        return view('livewire.academicsetting.academicyearform');
    }
}
