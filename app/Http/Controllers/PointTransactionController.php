<?php

namespace App\Http\Controllers;

use App\Models\PointRule;
use App\Models\PointTransaction;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PointTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = $this->filteredTransactions($request)
            ->latest('occurred_at')
            ->paginate(10)
            ->withQueryString();
        $students = Student::with('user')
            ->where('status', 'active')
            ->orderBy('nis')
            ->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('point-transactions.index', compact(
            'transactions',
            'students',
            'classes'
        ));
    }

    public function export(Request $request)
    {
        return response()->streamDownload(function () use ($request) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                'occurred_at',
                'nis',
                'student_name',
                'class_name',
                'type',
                'rule_code',
                'rule_name',
                'points',
                'notes',
                'recorded_by',
                'evidence_path',
            ], ',', '"', '');

            $this->filteredTransactions($request)
                ->orderByDesc('occurred_at')
                ->get()
                ->each(function (PointTransaction $transaction) use ($output) {
                    fputcsv($output, [
                        $transaction->occurred_at->format('Y-m-d H:i:s'),
                        self::safeCsvCell($transaction->student->nis),
                        self::safeCsvCell($transaction->student->user->name),
                        self::safeCsvCell($transaction->student->schoolClass->name),
                        $transaction->type === 'reward' ? 'Reward' : 'Pelanggaran',
                        self::safeCsvCell($transaction->rule->code),
                        self::safeCsvCell($transaction->rule->name),
                        $transaction->points,
                        self::safeCsvCell($transaction->notes ?? ''),
                        self::safeCsvCell($transaction->recorder->name),
                        self::safeCsvCell($transaction->evidence_path ?? ''),
                    ], ',', '"', '');
                });

            fclose($output);
        }, 'transaksi-poin.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function filteredTransactions(Request $request): Builder
    {
        return PointTransaction::with([
            'student.user',
            'student.schoolClass',
            'rule',
            'recorder',
        ])
            ->when($request->type, fn ($q, $type) => $q->where('type', $type))
            ->when($request->student_id, fn ($q, $id) => $q->where('student_id', $id))
            ->when($request->class_id, function ($q, $classId) {
                $q->whereHas(
                    'student',
                    fn ($student) => $student->where('school_class_id', $classId)
                );
            })
            ->when(
                $request->date_from,
                fn ($q, $date) => $q->whereDate('occurred_at', '>=', $date)
            )
            ->when(
                $request->date_to,
                fn ($q, $date) => $q->whereDate('occurred_at', '<=', $date)
            );
    }

    private static function safeCsvCell(string $value): string
    {
        return preg_match('/^[=+@\\-\t\r]/', $value) ? "'{$value}" : $value;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = in_array($request->query('type'), ['violation', 'reward'], true)
            ? $request->query('type')
            : 'violation';
        $students = Student::with(['user', 'schoolClass'])
            ->where('status', 'active')
            ->orderBy('nis')
            ->get();
        $rules = PointRule::where('type', $type)
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('point-transactions.create', compact(
            'type',
            'students',
            'rules'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', 'in:violation,reward'],
            'point_rule_id' => ['required', 'exists:point_rules,id'],
            'occurred_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);
        $rule = PointRule::whereKey($data['point_rule_id'])
            ->where('type', $data['type'])
            ->where('is_active', true)
            ->firstOrFail();
        $path = $request->hasFile('evidence')
            ? $request->file('evidence')->store('point-evidence', 'public')
            : null;
        $transaction = PointTransaction::create([
            'student_id' => $data['student_id'],
            'point_rule_id' => $rule->id,
            'recorded_by' => auth()->id(),
            'type' => $rule->type,
            'points' => $rule->points,
            'occurred_at' => $data['occurred_at'],
            'notes' => $data['notes'] ?? null,
            'evidence_path' => $path,
        ]);

        return redirect()->route('point-transactions.show', $transaction)
            ->with('success', 'Poin berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PointTransaction $pointTransaction)
    {
        $pointTransaction->load([
            'student.user',
            'student.schoolClass',
            'rule',
            'recorder',
        ]);

        return view('point-transactions.show', compact('pointTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointTransaction $pointTransaction)
    {
        if ($pointTransaction->evidence_path) {
            Storage::disk('public')->delete($pointTransaction->evidence_path);
        }
        $pointTransaction->delete();

        return redirect()->route('point-transactions.index')
            ->with('success', 'Transaksi poin dihapus.');
    }
}
