<?php

namespace App\Http\Controllers;

use App\Models\PointRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class PointRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pointRules = PointRule::orderBy('type')
            ->orderBy('category')
            ->orderBy('code')
            ->paginate(10);

        return view('point-rules.index', compact('pointRules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('point-rules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active');

        PointRule::create($data);

        return redirect()->route('point-rules.index')
            ->with('success', 'Aturan poin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PointRule $pointRule)
    {
        return view('point-rules.edit', compact('pointRule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PointRule $pointRule)
    {
        $data = $request->validate($this->rules($pointRule));
        $data['is_active'] = $request->boolean('is_active');
        $pointRule->update($data);
        return redirect()->route('point-rules.index')
            ->with('success', 'Aturan poin diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointRule $pointRule)
    {
        if ($pointRule->pointTransactions()->exists()) {
            return back()->withErrors([
                'point_rule' => 'Aturan sudah dipakai pada transaksi dan tidak dapat dihapus.'
            ]);
        }
        $pointRule->delete();
        return back()->with('success', 'Aturan poin dihapus.');
    }

    private function rules(?PointRule $pointRule = null): array
    {
        return [
            'code' => [
                'required',
                'max:20',
                Rule::unique('point_rules', 'code')->ignore($pointRule?->id),
            ],
            'type' => ['required', 'in:violation,reward'],
            'category' => ['required', 'max:100'],
            'name' => ['required', 'max:255'],
            'points' => ['required', 'integer', 'min:1', 'max:1000'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
