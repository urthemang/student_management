<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;

class StudentChart extends ChartWidget
{
    protected static ?string $heading = 'Students Per Subjects';
    protected function getData(): array
    {
        // Fetch users and their associated subjects
        $users = User::where('is_admin', 0)->with('userSubjects.subject')->get();
    
        // Count users per subject
        $subjectCounts = [];
        foreach ($users as $user) {
            foreach ($user->userSubjects as $userSubject) {
                $subjectName = $userSubject->subject->name ?? 'Unknown Subject';
                if (!isset($subjectCounts[$subjectName])) {
                    $subjectCounts[$subjectName] = 0;
                }
                $subjectCounts[$subjectName]++;
            }
        }
    
        // Separate keys (labels) and values (counts)
        $labels = array_keys($subjectCounts); // Subject names
        $data = array_values($subjectCounts); // User counts per subject
    
        return [
            'datasets' => [
                [
                    'label' => 'Number of Students Per Subject',
                    'data' => $data, // Number of students for each subject
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)', // Optional for filled charts
                    'fill' => false, // Set to true if you want the area under the line to be filled
                ],
            ],
            'labels' => $labels, // Subject names
        ];
    }
    


    protected function getType(): string
    {
        return 'bar';
    }
}
