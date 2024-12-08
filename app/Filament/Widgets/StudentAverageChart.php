<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;

class StudentAverageChart extends ChartWidget
{
    protected static ?string $heading = 'Student Averages';

    protected function getData(): array
    {
        // Fetch all students with their userSubjects and calculate their average grade
        $students = User::where('is_admin', 0)
            ->with('userSubjects')
            ->get()
            ->map(function ($student) {
                $average = $student->userSubjects->avg('grade');
                return [
                    'name' => $student->name,
                    'average_grade' => $average,
                ];
            })
            ->filter(function ($data) {
                // Filter students with average grades between 60 and 99
                return $data['average_grade'] >= 60 && $data['average_grade'] <= 99;
            });
    
        // Extract names (labels) and average grades (data)
        $labels = $students->pluck('name')->toArray();
        $data = $students->pluck('average_grade')->toArray();
    
        return [
            'datasets' => [
                [
                    'label' => 'Average Grades (60-99)',
                    'data' => $data, // Average grades of students
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)', // Optional for a filled graph
                    'fill' => false,
                ],
            ],
            'labels' => $labels, // Student names
        ];
    }
    

    protected function getType(): string
    {
        return 'line';
    }
}
