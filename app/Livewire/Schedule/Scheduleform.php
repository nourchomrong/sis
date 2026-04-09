<?php

namespace App\Livewire\Schedule;

use Livewire\Component;
use App\Models\Schedule;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;

class Scheduleform extends Component
{
    public $isSaving = false;
    public $showModal = true;
    public $schedule_id = null;
    public $isEdit = false;

    public $class_id;
    public $subject_id;
    public $teacher_id;
    public $day_of_week;
    public $start_time;
    public $end_time;
    public $disableClassSelection = false;

    protected $listeners = [
        'edit-schedule' => 'loadSchedule',
        'closeModal' => 'closeModal',
    ];

    protected $rules = [
        'class_id' => 'required|exists:classes,class_id',
        'subject_id' => 'required|exists:subjects,subject_id',
        'teacher_id' => 'nullable|exists:teachers,teacher_id',
        'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ];

    public function mount($scheduleId = null, $classId = null)
    {
        if ($scheduleId) {
            $this->loadSchedule($scheduleId);
        }
        if ($classId) {
            $this->class_id = $classId;
            $this->disableClassSelection = true;
        }
    }

    public function loadSchedule($data)
    {
        $schedule_id = is_array($data) ? ($data['schedule_id'] ?? null) : $data;
        if (!$schedule_id) return;

        $schedule = Schedule::find($schedule_id);
        if (!$schedule) return;

        $this->schedule_id = $schedule->schedule_id;
        $this->class_id = $schedule->class_id;
        $this->subject_id = $schedule->subject_id;
        $this->teacher_id = $schedule->teacher_id;
        $this->day_of_week = $schedule->day_of_week;
        // Format times from H:i:s to H:i for HTML time inputs
        $this->start_time = Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i');
        $this->end_time = Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i');

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('closeModal');
    }

    public function submit()
    {
        $this->isSaving = true;
        $this->validate();

        try {
            if ($this->isEdit) {
                $schedule = Schedule::find($this->schedule_id);
                $schedule->update([
                    'class_id' => $this->class_id,
                    'subject_id' => $this->subject_id,
                    'teacher_id' => $this->teacher_id,
                    'day_of_week' => $this->day_of_week,
                    'start_time' => $this->start_time . ':00',
                    'end_time' => $this->end_time . ':00',
                ]);
                $message = 'Schedule updated successfully';
            } else {
                Schedule::create([
                    'class_id' => $this->class_id,
                    'subject_id' => $this->subject_id,
                    'teacher_id' => $this->teacher_id,
                    'day_of_week' => $this->day_of_week,
                    'start_time' => $this->start_time . ':00',
                    'end_time' => $this->end_time . ':00',
                    'status' => 'A',
                ]);
                $message = 'Schedule created successfully';
            }

            $this->dispatch('schedule-created', $message);
            $wasEdit = $this->isEdit;
            $this->resetForm();
            if ($wasEdit) {
                $this->showModal = false;
                $this->dispatch('closeModal');
            }
        } catch (\Exception $e) {
            $this->addError('submit', 'Error saving schedule: ' . $e->getMessage());
        }

        $this->isSaving = false;
    }

    public function getClassesProperty()
    {
        return Classes::where('status', 'A')->orderBy('class_name')->get();
    }

    public function getSubjectsProperty()
    {
        return Subject::where('status', 'A')->orderBy('subject_name')->get();
    }

    public function getTeachersProperty()
    {
        return Teacher::where('status', 'A')->orderBy('en_fullname')->get();
    }

    public function resetForm()
    {
        $this->schedule_id = null;
        if (!$this->disableClassSelection) {
            $this->class_id = null;
        }
        $this->subject_id = null;
        $this->teacher_id = null;
        $this->day_of_week = null;
        $this->start_time = null;
        $this->end_time = null;
        $this->isEdit = false;
    }
    public function render()
    {
        return view('livewire.schedule.scheduleform', [
            'classes' => $this->classes,
            'subjects' => $this->subjects,
            'teachers' => $this->teachers,
        ]);
    }
}
