<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount('students')
            ->orderByDesc('academic_year')
            ->orderBy('name')
            ->paginate(10);

        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        SchoolClass::create($request->validate(...array_values($this->rules())));

        return redirect()->route('classes.index')
            ->with('success', 'Kelas ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $class)
    {
        $students = $class->students()
            ->with(['user', 'pointTransactions'])
            ->where('status', 'active')
            ->get()
            ->map(function ($student) {
                $student->violation_points = $student->pointTransactions
                    ->where('type', 'violation')->sum('points');
                $student->reward_points = $student->pointTransactions
                    ->where('type', 'reward')->sum('points');
                return $student;
            });

        return view('classes.show', compact('class', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class)
    {
        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        $class->update($request->validate(...array_values($this->rules())));

        return redirect()->route('classes.index')
            ->with('success', 'Kelas diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class)
    {
        if ($class->students()->exists()) {
            return back()->withErrors([
                'class' => 'Kelas masih memiliki siswa dan tidak dapat di hapus.'
            ]);
        }
        $class->delete();
        return back()->with('success', 'Kelas dihapus.');
    }

    private function rules(): array
    {
        return [
            'rules' => [
                'name' => ['required', 'max:100'],
                'grade' => ['required', 'max:10'],
                'major' => ['required', 'max:100'],
                'academic_year' => ['required', 'max:20'],
            ],
            'messages' => [
                'required' => ':attribute wajib di isi',
                'max' => ':attribute maximal :max karakter',
            ],
            'attributes' => [
                'name' => 'nama',
                'grade' => 'kelas',
                'major' => 'Jurusam',
                'academic_year' => 'Tahun ajaran',      
            ],
        ];
    }
}
