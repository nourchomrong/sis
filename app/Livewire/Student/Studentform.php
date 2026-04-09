<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Student;
use Livewire\WithFileUploads;
use App\Services\PhotoService;
use Illuminate\Support\Facades\DB;

class Studentform extends Component
{
    use WithFileUploads;

    public $isSaving = false;
    public $showModal = true;
    public $student_id = null;
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
        'edit-student' => 'loadStudent',
        'closeModal' => 'closeModal',
    ];

    protected $rules = [
        'en_first_name' => 'required|string',
        'en_last_name' => 'required|string',
        'kh_first_name' => 'required|string',
        'kh_last_name' => 'required|string',
        'gender' => 'required',
        'dateofbirth' => 'required|date',
        'placeofbirth' => 'required|string',
        'address' => 'nullable|string',
        'phone' => 'nullable|string',
        'email' => 'nullable|email',
        'photo' => 'nullable|image|max:2048',
    ];

    public function mount($studentId = null)
    {
        if ($studentId) {
            $this->student_id = $studentId;
            $this->loadStudent($studentId);
        }
    }

    // Update full names when first or last names change
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

    public function loadStudent($data)
    {
        $student_id = is_array($data) ? ($data['student_id'] ?? null) : $data;
        if (! $student_id) return;

        $student = Student::find($student_id);

        if ($student) {
            $this->student_id = $student->student_id;
            $this->student_code = str_pad($student->student_id, 5, '0', STR_PAD_LEFT);

            // Split names into first + last
            $enNames = explode(' ', $student->en_fullname, 2);
            $this->en_first_name = $enNames[0] ?? '';
            $this->en_last_name = $enNames[1] ?? '';
            $this->en_fullname = $student->en_fullname;

            $khNames = explode(' ', $student->kh_fullname, 2);
            $this->kh_first_name = $khNames[0] ?? '';
            $this->kh_last_name = $khNames[1] ?? '';
            $this->kh_fullname = $student->kh_fullname;

            $this->gender = $student->gender;
            $this->dateofbirth = $student->dateofbirth;
            $this->placeofbirth = $student->birthplace;
            $this->address = $student->address;
            $this->phone = $student->phone;
            $this->email = $student->email;

            $this->isEdit = true;
            $this->showModal = true;
        }
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

        // Check for duplicate student
        $existing = Student::where('en_fullname', $this->en_fullname)
            ->where('kh_fullname', $this->kh_fullname)
            ->where('gender', $this->gender)
            ->where('dateofbirth', $this->dateofbirth);

        if ($this->isEdit) {
            $existing = $existing->where('student_id', '!=', $this->student_id);
        }

        $existing = $existing->first();

        if ($existing) {
            $this->addError('duplicate', 'A student with the same name, gender, and date of birth already exists.');
            $this->isSaving = false;
            return;
        }

        $photoService = app(PhotoService::class);

        if ($this->isEdit) {
            $student = Student::find($this->student_id);

            $student?->update([
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
            $student = Student::create([
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

        // Handle photo record
        if ($this->photo && $student) {
            $photoService->uploadPhoto($this->photo, $student);
        }

        $message = $this->isEdit ? 'Student updated successfully!' : 'Student created successfully!';

        $this->resetForm();
        $this->dispatch('closeModal');
        $this->dispatch('student-created', ['message' => $message]);
    }

    public function resetForm()
    {
        $this->student_id = null;
        $this->isEdit = false;
        $this->student_code = '00000';

        $this->en_first_name = $this->en_last_name = $this->en_fullname = null;
        $this->kh_first_name = $this->kh_last_name = $this->kh_fullname = null;

        $this->gender = $this->dateofbirth = $this->placeofbirth = null;
        $this->address = $this->phone = $this->email = null;
        $this->photo = null;
    }

    public function render()
    {
        return view('livewire.student.studentform');
    }
}