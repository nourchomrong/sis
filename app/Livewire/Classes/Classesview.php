<?php

namespace App\Livewire\Classes;

use Livewire\Component;
use App\Models\Classes;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Schedule;

class Classesview extends Component
{
    use WithPagination;
    public $query = '';
    public $selectedClasses = null;
    public $selectedClassesId = null;
    public $showClassesDetail = false;
    public $showClassesForm = false;
    public $showDeleteConfirm = false;
    public $classesToDelete = null;
    public $showEditConfirm = false;

    public $classesToEdit = null;
    public $selectedSchedules = null;
    public $selectedClassIdForSchedule = null;
    public $scheduleQuery = '';
    public $showScheduleView = false;
    public $showScheduleForm = false;
    public $selectedScheduleId = null;
    public $showScheduleDeleteConfirm = false;
    public $scheduleToDelete = null;
    public $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    protected $paginationTheme = 'tailwind'; // or tailwind
    
    protected $listeners = [
        'classesCreated' => 'handleClassesCreated',
        'closeModal' => 'handleCloseModal',
        'schedule-created' => 'handleScheduleCreated',
    ];
    protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
];

    /**
     * Handle refresh event from model observer
     */
    #[On('refresh-classes')]
    public function refreshClasses($data = [])
    {
        $this->resetPage();
    }

    public function viewClassroom($id)
    {
        $this->selectedClasses = Classes::find($id);
        $this->showClassesDetail = true;
    }
    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function updatedScheduleQuery()
    {
        // Refresh the schedule list when searching
        $this->handleScheduleCreated();
    }

    public function handleCloseModal()
    {
        $this->showClassesForm = false;
        $this->selectedClassesId = null;
        $this->showScheduleForm = false;
        $this->selectedScheduleId = null;
        $this->selectedClassIdForSchedule = null;
        $this->scheduleQuery = '';
    }

    public function handleClassesCreated()
    {
        // Refresh the classes list when a new class is created
        $this->resetPage();
    }

    public function handleScheduleCreated($data = null)
    {
        // Always refresh the schedules when viewing a class's schedules
        if ($this->selectedClassIdForSchedule) {
            $this->selectedSchedules = Schedule::where('class_id', $this->selectedClassIdForSchedule)
                ->where('status', 'A')
                ->with('subject', 'teacher')
                ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
                ->orderBy('start_time')
                ->get();
        }
    }

    public function getFilteredSchedulesProperty()
    {
        if (!$this->selectedSchedules) {
            return collect();
        }

        $filteredSchedules = $this->selectedSchedules;

        if ($this->scheduleQuery) {
            $query = strtolower($this->scheduleQuery);
            $filteredSchedules = $filteredSchedules->filter(function ($schedule) use ($query) {
                return str_contains(strtolower($schedule->subject->subject_name ?? ''), $query)
                    || str_contains(strtolower($schedule->teacher->en_fullname ?? ''), $query)
                    || str_contains(strtolower($schedule->day_of_week ?? ''), $query)
                    || str_contains(strtolower($schedule->start_time ?? ''), $query)
                    || str_contains(strtolower($schedule->end_time ?? ''), $query);
            });
        }

        return $filteredSchedules->sortBy(function ($schedule) {
            return array_search($schedule->day_of_week, $this->daysOrder) * 10000
                + intval(str_replace(':', '', substr($schedule->start_time, 0, 5)));
        })->values();
    }

    public function viewSchedule($classId)
    {
        $this->selectedSchedules = Schedule::where('class_id', $classId)
            ->where('status', 'A')
            ->with('subject', 'teacher')
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();
        $this->selectedClassIdForSchedule = $classId;
        $this->showScheduleView = true;
    }

    public function editClasses($id)
    {
        $this->selectedClassesId = $id;
        $this->showClassesForm = true;
        $this->dispatch('classes-created', 'message' , 'edit-classes', $id);
    }

    public function confirmEditClass($id)
    {
        $this->classesToEdit = $id;
        $this->showEditConfirm = true;
    }
    public function editClassesConfirmed($id)
    {
      $this->selectedClassesId = $id;
        $this->showClassesForm = true;
        $this->dispatch('classroom.classroomform', 'edit-classroom', $id);
        $this->showEditConfirm = false;
        $this->classesToEdit = null;
    }

    public function cancelEdit()
    {
        $this->showEditConfirm = false;
        $this->classesToEdit = null;
    }
    public function confirmDeleteClass($id)
    {
        $this->classesToDelete = $id;
        $this->showDeleteConfirm = true;
        
    }

    public function deleteClasses($id)
    {
        $classes = Classes::find($id);
        if ($classes) {
            $classes->update(['status' => 'I']); // soft delete
        }
         $this->dispatch('classes-created', ['message' => 'Classes deleted successfully']);
        $this->showDeleteConfirm = false;
        $this->classesToDelete = null;
    }
    public function openScheduleForm()
    {
        $this->selectedScheduleId = null;
        $this->showScheduleForm = true;
    }

    public function editSchedule($scheduleId)
    {
        $this->selectedScheduleId = $scheduleId;
        $this->showScheduleForm = true;
    }

    public function confirmDeleteSchedule($scheduleId)
    {
        $this->scheduleToDelete = $scheduleId;
        $this->showScheduleDeleteConfirm = true;
    }

    public function deleteSchedule($scheduleId)
    {
        $schedule = Schedule::find($scheduleId);
        if ($schedule) {
            $schedule->update(['status' => 'I']);
        }
        $this->handleScheduleCreated(); // Refresh schedules
        $this->dispatch('schedule-created', ['message' => 'Schedule deleted successfully']);
        $this->showScheduleDeleteConfirm = false;
        $this->scheduleToDelete = null;
    }

    public function cancelDeleteSchedule()
    {
        $this->showScheduleDeleteConfirm = false;
        $this->scheduleToDelete = null;
    }

    public function closeScheduleView()
    {
        $this->showScheduleView = false;
        $this->handleScheduleCreated();
    }
    public function render()
    {
        try {
            $classes = Classes::with(['year', 'classroom', 'teacher'])
                ->where('status', 'A')
                ->when($this->query, function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('class_name', 'like', "%{$this->query}%")
                            ->orWhere('grade_level', 'like', "%{$this->query}%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('livewire.classes.classesview', [
                'classes' => $classes,
                'filteredSchedules' => $this->filteredSchedules,
            ]);
        } catch (\Exception $e) {
            // Log the error and return empty collection
            \Log::error('Error loading classes: ' . $e->getMessage());
            return view('livewire.classes.classesview', [
                'classes' => collect(),
                'filteredSchedules' => collect(),
            ]);
        }
    }
}
