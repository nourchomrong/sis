<?php

namespace App\Livewire\Teacher;

use Livewire\Component;
use App\Models\Teacher;
use Livewire\WithFileUploads;
use App\Services\PhotoService;
use Illuminate\Support\Facades\DB;

class Teacherform extends Component
{
    use WithFileUploads;

    public $isSaving = false;
    public $showModal = true;
    public $teacher_id = null;
    public $isEdit = false;

    // Name fields
    public $en_first_name;
    public $en_last_name;
    public $en_fullname;
    public $kh_first_name;
    public $kh_last_name;
    public $kh_fullname;

    public $gender;
    public $dateofbirth;
    public $placeofbirth;
    public $address;
    public $phone;
    public $email;
    public $photo;

    protected $listeners = [
        'edit-teacher' => 'loadTeacher',
        'closeModal' => 'closeModal',
    ];

    protected $rules = [
        'en_first_name' => 'required|string|max:255',
        'en_last_name' => 'required|string|max:255',
        'kh_first_name' => 'nullable|string|max:255',
        'kh_last_name' => 'nullable|string|max:255',
        'gender' => 'required',
        'dateofbirth' => 'required|date',
        'placeofbirth' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'photo' => 'nullable|image|max:2048',
    ];

    public function mount($teacherId = null)
    {
        if ($teacherId) {
            $this->loadTeacher($teacherId);
        }
    }

    // Update full names automatically when first or last names change
    public function updatedEnFirstName() { $this->updateEnFullname(); }
    public function updatedEnLastName()  { $this->updateEnFullname(); }
    public function updatedKhFirstName() { $this->updateKhFullname(); }
    public function updatedKhLastName()  { $this->updateKhFullname(); }

    protected function updateEnFullname()
    {
        $this->en_fullname = trim($this->en_first_name . ' ' . $this->en_last_name);
    }

    protected function updateKhFullname()
    {
        $this->kh_fullname = trim($this->kh_first_name . ' ' . $this->kh_last_name);
    }

    public function loadTeacher($data)
    {
        $teacher_id = is_array($data) ? ($data['teacher_id'] ?? null) : $data;
        if (! $teacher_id) return;

        $teacher = Teacher::find($teacher_id);
        if (!$teacher) return;

        $this->teacher_id = $teacher->teacher_id;


        // Split full names into first + last
        $enNames = explode(' ', $teacher->en_fullname, 2);
        $this->en_first_name = $enNames[0] ?? '';
        $this->en_last_name = $enNames[1] ?? '';
        $this->en_fullname = $teacher->en_fullname;

        $khNames = explode(' ', $teacher->kh_fullname ?? '', 2);
        $this->kh_first_name = $khNames[0] ?? '';
        $this->kh_last_name = $khNames[1] ?? '';
        $this->kh_fullname = $teacher->kh_fullname ?? null;

        $this->gender = $teacher->gender;
        $this->dateofbirth = $teacher->dateofbirth;
        $this->placeofbirth = $teacher->birthplace ?? null;
        $this->address = $teacher->address ?? null;
        $this->phone = $teacher->phone ?? null;
        $this->email = $teacher->email ?? null;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function removePhoto()
    {
        $this->reset('photo');
    }

    public function submit()
    {
        $this->isSaving = true;
        $this->validate();

        // Check for duplicate teacher
        $existing = Teacher::where('en_fullname', $this->en_fullname)
            ->where('kh_fullname', $this->kh_fullname)
            ->where('gender', $this->gender)
            ->where('dateofbirth', $this->dateofbirth);

        if ($this->isEdit) {
            $existing = $existing->where('teacher_id', '!=', $this->teacher_id);
        }

        $existing = $existing->first();

        if ($existing) {
            $this->addError('duplicate', 'A teacher with the same name, gender, and date of birth already exists.');
            $this->isSaving = false;
            return;
        }

        $photoService = app(PhotoService::class);

        if ($this->isEdit) {
            $teacher = Teacher::find($this->teacher_id);
            $teacher?->update([
                'en_fullname' => $this->en_fullname,
                'kh_fullname' => $this->kh_fullname,
                'gender' => $this->gender,
                'dateofbirth' => $this->dateofbirth,
                'birthplace' => $this->placeofbirth,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);
        } else {
            $teacher = Teacher::create([
                'en_fullname' => $this->en_fullname,
                'kh_fullname' => $this->kh_fullname,
                'gender' => $this->gender,
                'dateofbirth' => $this->dateofbirth,
                'birthplace' => $this->placeofbirth,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'status' => 'A',
            ]);
        }

        // Handle photo
        if ($this->photo && $teacher) {
            $photoService->uploadPhoto($this->photo, $teacher);
        }

        $message = $this->isEdit ? 'Teacher updated successfully!' : 'Teacher created successfully!';

        $this->resetForm();
        $this->dispatch('closeModal');
        $this->dispatch('teacher-created', ['message' => $message]);
    }

    public function resetForm()
    {
        $this->teacher_id = null;
        $this->isEdit = false;

        $this->en_first_name = $this->en_last_name = $this->en_fullname = null;
        $this->kh_first_name = $this->kh_last_name = $this->kh_fullname = null;

        $this->gender = $this->dateofbirth = $this->placeofbirth = null;
        $this->address = $this->phone = $this->email = null;
        $this->photo = null;
    }

    public function render()
    {
        return view('livewire.teacher.teacherform');
    }
}