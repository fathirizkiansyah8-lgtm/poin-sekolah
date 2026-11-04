<?php

namespace App\Http\Controllers;

use App\Services\PointSummaryService;
use App\Services\StudentChangeLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentPointController extends Controller
{
    public function editPassword(): View
    {
        return view('student.password');
    }

    public function updatePassword(Request $request, StudentChangeLogger $changeLogger): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $student = $request->user()->student;
        abort_unless($student, 404);

        DB::transaction(function () use ($request, $validated, $student, $changeLogger) {
            $request->user()->update([
                'password' => $validated['password'],
            ]);

            $changeLogger->record($student, $request->user(), [
                'password' => ['changed' => true],
            ]);
        });

        return redirect()->route('student.password.edit')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PointSummaryService $summaryService)
    {
        $student = auth()->user()->student;
        abort_unless($student, 404);

        $student->load(['user', 'schoolClass']);
        $summary = $summaryService->summarize($student);

        $transactions = $student->pointTransactions()
            ->with(['rule', 'recorder'])
            ->latest('occurred_at')
            ->paginate(10);

        $followUps = $student->followUps()
            ->with('handler')
            ->latest('follow_up_date')
            ->get();

        return view('student.points', compact(
            'student',
            'summary',
            'transactions',
            'followUps'
        ));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
