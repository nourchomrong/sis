<?php

namespace App\Livewire\Schedule;

use Livewire\Component;
use App\Models\Schedule;
use Livewire\WithPagination;

class Scheduleview extends Component
{
    use WithPagination;

    public $query = '';
    public $selectedSchedule = null;
    public $selectedScheduleId = null;
    public $showScheduleDetail = false;
    public $showScheduleForm = false;
    public $message;
    public $showModal = false;
    public $showDeleteConfirm = false;
    public $scheduleToDelete = null;
    public $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    protected $listeners = [
        'scheduleCreated' => 'handleScheduleCreated',
        'closeModal' => 'handleCloseModal',
    ];

    protected $queryString = [
        'query' => ['except' => ''],
    ];

    public function handleScheduleCreated($msg)
    {
        $this->message = $msg;
    }

    public function viewSchedule($id)
    {
        $this->selectedSchedule = Schedule::with('class', 'subject', 'teacher')->find($id);
        $this->showScheduleDetail = true;
    }

    public function handleCloseModal()
    {
        $this->showScheduleForm = false;
        $this->selectedScheduleId = null;
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function getSchedulesProperty()
    {
        return Schedule::with('class', 'subject', 'teacher')
            ->where('status', 'A')
            ->when($this->query, function ($q) {
                $q->whereHas('class', function ($sub) {
                    $sub->where('class_name', 'like', "%{$this->query}%");
                })->orWhereHas('subject', function ($sub) {
                    $sub->where('subject_name', 'like', "%{$this->query}%");
                })->orWhere('day_of_week', 'like', "%{$this->query}%");
            })
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->paginate(10);
    }

    public function editSchedule($id)
    {
        $this->selectedScheduleId = $id;
        $this->showScheduleForm = true;
        $this->dispatch('schedule.scheduleform', 'edit-schedule', $id);
    }

    public function confirmDeleteSchedule($id)
    {
        $this->scheduleToDelete = $id;
        $this->showDeleteConfirm = true;
    }

    public function deleteSchedule($id)
    {
        $schedule = Schedule::find($id);
        if ($schedule) {
            $schedule->update(['status' => 'I']);
        }
        $this->dispatch('schedule-created', ['message' => 'Schedule deleted successfully']);
        $this->showDeleteConfirm = false;
        $this->scheduleToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->scheduleToDelete = null;
    }

    public function openModal($scheduleId = null)
    {
        $this->selectedScheduleId = $scheduleId;
        $this->showScheduleForm = true;
    }

    public function render()
    {
        return view('livewire.schedule.scheduleview', [
            'schedules' => $this->schedules,
        ]);
    }
}
