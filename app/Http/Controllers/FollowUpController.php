<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, Student $student)
    {
        $data = $request->validate([
            'point-transaction_id' => ['nullable', 'exists:point_transactions,id'],
            'follow_up_type' => [
                'required',
                'in:coaching,warning,parent_call,appreciation,certificate,other',
           ],
           'follow_up_date' => ['required', 'date'],
            'notes' => ['required', 'string', 'max:3000'],
        ]);

        $student->followUps()->create([
            ...$data,
            'handled_by' => auth()->id(),
        ]);

        return back()->with('success', 'Tindak lanjut disimpan.');
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
