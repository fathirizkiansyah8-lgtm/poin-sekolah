<?php

namespace App\Services;

use App\Models\Student;

class PointSummaryService
{
    public function summarize(Student $student) : array
    {
        $violation = (int) $student->pointTransactions()
            ->where ('type', 'violation')->sum('points');

        $reward = (int) $student->pointTransactions()
            ->where ('type', 'reward')->sum('points');

            return [
                'violation_points' => $violation,
                'reward_points' => $reward,
                'net_points' => $reward - $violation,
                'violation_status' => $this->violationStatus($violation),
                'reward_level' =>$this->rewardLevel($reward),
            ];
   }

   public function violationStatus (int $points): string
   {
    return match (true) {
        $points >= 100 => 'Tidak Lanjut Intensif',
        $points >= 75 => 'SP 2',
        $points >= 50 => 'SP 1',
        $points >= 25 => 'Pembinaan',
        default       => 'Aman',
    };
   }

   public function rewardLevel (int $points): string 
   {
    return match (true) {
        $points >= 100 => 'Gold',
        $points >= 50 => 'Silver',
        $points >= 25 => 'Bronze',
        default       => 'Starter',
    };
   }
}