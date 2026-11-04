<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Services\PointSummaryService;
use App\Services\StudentChangeLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function downloadTemplate()
    {
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['nis', 'name', 'email', 'class_name', 'grade', 'gender', 'status', 'password'], ',', '"', '');
            fputcsv($output, [
                'CONTOH001',
                'Nama Siswa Contoh',
                'contoh.siswa@example.com',
                'XI RPL 1',
                'XI',
                'L',
                'active',
                'GantiPassword123',
            ], ',', '"', '');
            fclose($output);
        }, 'template-import-siswa.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function export()
    {
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['nis', 'name', 'email', 'class_name', 'grade', 'gender', 'status', 'password'], ',', '"', '');

            $students = Student::with(['user', 'schoolClass'])
                ->orderBy('nis')
                ->get();

            foreach ($students as $student) {
                fputcsv($output, [
                    self::safeCsvCell($student->nis),
                    self::safeCsvCell($student->user->name),
                    self::safeCsvCell($student->user->email),
                    self::safeCsvCell($student->schoolClass->name),
                    self::safeCsvCell($student->schoolClass->grade),
                    $student->gender,
                    $student->status,
                    '',
                ], ',', '"', '');
            }

            fclose($output);
        }, 'data-siswa.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function import(Request $request, StudentChangeLogger $changeLogger)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:5120', 'extensions:csv,txt'],
        ]);

        $handle = fopen($validated['file']->getRealPath(), 'r');

        if ($handle === false) {
            throw ValidationException::withMessages(['file' => 'File CSV tidak dapat dibaca.']);
        }

        $headers = fgetcsv($handle, null, ',', '"', '');

        if (! $headers) {
            fclose($handle);
            throw ValidationException::withMessages(['file' => 'File CSV kosong atau tidak memiliki header.']);
        }

        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
        $headers = array_map(fn ($header) => strtolower(trim($header)), $headers);
        $requiredHeaders = ['nis', 'name', 'email', 'class_name', 'grade', 'gender', 'status', 'password'];

        if (count(array_unique($headers)) !== count($headers) || array_diff($requiredHeaders, $headers) !== []) {
            fclose($handle);
            throw ValidationException::withMessages([
                'file' => 'Header harus unik dan mencakup: '.implode(', ', $requiredHeaders).'.',
            ]);
        }

        $rows = [];
        $errors = [];
        $seenNis = [];
        $seenEmails = [];
        $rowNumber = 1;

        while (($values = fgetcsv($handle, null, ',', '"', '')) !== false) {
            $rowNumber++;

            if (count(array_filter($values, fn ($value) => trim((string) $value) !== '')) === 0) {
                continue;
            }

            if (count($values) > count($headers)) {
                $errors[] = "Baris {$rowNumber}: jumlah kolom melebihi header CSV.";

                continue;
            }

            $row = array_combine($headers, array_pad($values, count($headers), ''));
            $password = (string) ($row['password'] ?? '');
            $row = array_map(fn ($value) => trim((string) $value), $row);
            $row['password'] = trim($password) === '' ? null : $password;
            $student = Student::with(['user', 'schoolClass'])->where('nis', $row['nis'])->first();
            $schoolClass = SchoolClass::where('name', $row['class_name'])
                ->where('grade', $row['grade'])
                ->first();

            if (! $schoolClass) {
                $errors[] = "Baris {$rowNumber}: kombinasi nama kelas dan tingkat tidak ditemukan.";

                continue;
            }

            $validator = Validator::make($row, [
                'nis' => ['required', 'string', 'max:30', Rule::unique('students', 'nis')->ignore($student?->id)],
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($student?->user_id),
                ],
                'class_name' => ['required', 'string', 'max:100'],
                'grade' => ['required', 'string', 'max:10'],
                'gender' => ['required', Rule::in(['L', 'P'])],
                'status' => ['required', Rule::in(['active', 'inactive'])],
                'password' => [$student ? 'nullable' : 'required', 'string', 'min:6'],
            ]);

            if (isset($seenNis[$row['nis']])) {
                $validator->errors()->add('nis', 'NIS ini muncul lebih dari sekali dalam file.');
            }

            if (isset($seenEmails[strtolower($row['email'])]) && $seenEmails[strtolower($row['email'])] !== $row['nis']) {
                $validator->errors()->add('email', 'Email ini digunakan oleh lebih dari satu baris.');
            }

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $errors[] = "Baris {$rowNumber}: {$error}";
                }

                continue;
            }

            $seenNis[$row['nis']] = true;
            $seenEmails[strtolower($row['email'])] = $row['nis'];
            $rows[] = compact('row', 'student', 'schoolClass');
        }

        fclose($handle);

        if ($errors !== []) {
            throw ValidationException::withMessages(['file' => $errors]);
        }

        if ($rows === []) {
            throw ValidationException::withMessages(['file' => 'Tidak ada data siswa untuk diimpor.']);
        }

        DB::transaction(function () use ($rows, $request, $changeLogger) {
            foreach ($rows as $item) {
                $row = $item['row'];
                $student = $item['student'];
                $schoolClass = $item['schoolClass'];

                if ($student) {
                    $changes = [];

                    foreach (['name', 'email'] as $field) {
                        if ($student->user->{$field} !== $row[$field]) {
                            $changes[$field] = ['old' => $student->user->{$field}, 'new' => $row[$field]];
                        }
                    }

                    foreach (['nis', 'gender', 'status'] as $field) {
                        if ($student->{$field} !== $row[$field]) {
                            $changes[$field] = ['old' => $student->{$field}, 'new' => $row[$field]];
                        }
                    }

                    if ((int) $student->school_class_id !== (int) $schoolClass->id) {
                        $changes['school_class'] = [
                            'old' => $student->schoolClass->name,
                            'new' => $schoolClass->name,
                        ];
                    }

                    $userData = ['name' => $row['name'], 'email' => $row['email']];

                    if ($row['password'] !== null && ! Hash::check($row['password'], $student->user->password)) {
                        $userData['password'] = Hash::make($row['password']);
                        $changes['password'] = ['changed' => true];
                    }

                    $student->user->update($userData);
                    $student->update([
                        'nis' => $row['nis'],
                        'school_class_id' => $schoolClass->id,
                        'gender' => $row['gender'],
                        'status' => $row['status'],
                    ]);
                    $changeLogger->record($student, $request->user(), $changes);

                    continue;
                }

                $user = User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password']),
                    'role' => 'student',
                    'status' => 'active',
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'school_class_id' => $schoolClass->id,
                    'nis' => $row['nis'],
                    'gender' => $row['gender'],
                    'status' => $row['status'],
                ]);
            }
        });

        return redirect()->route('students.index')
            ->with('success', count($rows).' data siswa berhasil diimpor.');
    }

    private static function safeCsvCell(string $value): string
    {
        return preg_match('/^[=+@\\-\t\r]/', $value) ? "'{$value}" : $value;
    }

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
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%")
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

        $studentChanges = auth()->user()->role === 'admin'
            ? $student->changes()->with('actor')->latest()->get()
            : collect();

        $transactions = $student->pointTransactions()
            ->with(['rule', 'recorder'])
            ->latest('occurred_at')
            ->paginate(10);

        return view('students.show', compact(
            'student', 'summary', 'transactions', 'studentChanges'
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
    public function update(Request $request, Student $student, StudentChangeLogger $changeLogger)
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

        $student->load(['user', 'schoolClass']);
        $newClassName = SchoolClass::findOrFail($data['school_class_id'])->name;
        $changes = [];

        foreach (['name', 'email'] as $field) {
            if ($student->user->{$field} !== $data[$field]) {
                $changes[$field] = [
                    'old' => $student->user->{$field},
                    'new' => $data[$field],
                ];
            }
        }

        foreach (['nis', 'gender', 'status'] as $field) {
            if ($student->{$field} !== $data[$field]) {
                $changes[$field] = [
                    'old' => $student->{$field},
                    'new' => $data[$field],
                ];
            }
        }

        if ((int) $student->school_class_id !== (int) $data['school_class_id']) {
            $changes['school_class'] = [
                'old' => $student->schoolClass->name,
                'new' => $newClassName,
            ];
        }

        $passwordChanged = ! empty($data['password'])
            && ! Hash::check($data['password'], $student->user->password);

        if ($passwordChanged) {
            $changes['password'] = ['changed' => true];
        }

        DB::transaction(function () use ($student, $data, $changes, $changeLogger, $request, $passwordChanged) {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if ($passwordChanged) {
                $userData['password'] = Hash::make($data['password']);
            }

            $student->user->update($userData);

            $student->update([
                'nis' => $data['nis'],
                'school_class_id' => $data['school_class_id'],
                'gender' => $data['gender'],
                'status' => $data['status'],
            ]);

            $changeLogger->record($student, $request->user(), $changes);
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
