<?php

namespace App\Http\Controllers;

use App\Models\{student,PointTransaction};

class DashboardController extends Controller
{
    public function index ()
    {
        $user = auth()->user();

        if ($user->role == 'student') {
            return redirect()->route('student.points');
        }

        $totalStudents =student::where('status', 'active')->count();

        $todayViolations =PointTransaction::where('type', 'violation')
            ->whereDate('occurred_at', today())
            ->count();
        $todayRewards = PointTransaction::where('type', 'reward')
            ->whereDate('occurred_at', today())
            ->count();

        return view('dashboard', compact(
            'totalStudents', 'todayViolations', 'todayRewards'
        ));
    }
}
