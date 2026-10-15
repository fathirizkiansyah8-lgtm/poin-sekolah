<?php

namespace App\Http\Controllers;

use App\Models\{SchoolClass, Student, User};
use App\Services\PointSummaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
 {
        $students = Student::with(['user', 'schoolClass'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;

                $query->where(function ($sub) use ($q) {
                    $sub->where('nis', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($u) =>
                            $u->where('name', 'like', "%{$q}%")
                        );
                });
            })
            ->orderBy('nis')
            ->paginate(10)
            ->withQueryString();
         return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'nis' => ['required', 'max:30', 'unique:students,nis'],
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'gender' => ['required', 'in:L,P'],
        ]);
        
        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'school_class_id' => $data['school_class_id'],
                'nis' => $data['nis'],
                'gender' => $data['gender'],
                'status' => 'active',
            ]);
        });

        return redirect()->route('students.index')
            ->with('success', 'Siswa ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student, PointSummaryService $summaryService)
    {
        $student->load(['user', 'schoolClass']);
        $summary = $summaryService->summarize($student);

        $transactions = $student->pointTransactions()
            ->with(['rule', 'recorder'])
            ->latest('occurred_at')
            ->paginate(10);

        return view('students.show', compact(
            'student', 'summary', 'transactions'
        ));
     }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $student->load('user');

        $classes = SchoolClass::orderBy('name')->get();

        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $student->load('user');

        $data = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
            'password' => ['nullable', 'min:6'],
            'nis' => [
                'required', 'max:30',
                Rule::unique('students', 'nis')->ignore($student->id),
            ],
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'gender' => ['required', 'in:L,P'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        DB::transaction(function () use ($student, $data) {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

             if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $student->user->update($userData);

            $student->update([
                'nis' => $data['nis'],
                'school_class_id' => $data['school_class_id'],
                'gender' => $data['gender'],
                'status' => $data['status'],
             ]);
        });

        return redirect()->route('students.index')
            ->with('success', 'Data siswa diperbarui.');
     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->load('user');

        DB::transaction(function () use ($student) {
            $student->user->delete();
        });

        return redirect()->route('students.index')
            ->with('success', 'Data siswa dihapus.');
    }
}