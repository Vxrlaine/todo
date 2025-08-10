<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistik
        $stats = [
            'total' => $user->tasks()->count(),
            'completed' => $user->tasks()->completed()->count(),
            'pending' => $user->tasks()->pending()->count(),
            'overdue' => $user->tasks()->overdue()->count()
        ];
        
        // Tugas mendatang (7 hari ke depan)
        $upcomingTasks = $user->tasks()
            ->where('completed', false)
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->orderBy('due_date')
            ->get();
        
        // Tugas dikelompokkan berdasarkan tanggal untuk kalender
        $tasksByDate = $user->tasks()
            ->whereNotNull('due_date')
            ->get()
            ->groupBy(function($task) {
                return $task->due_date->format('Y-m-d');
            })
            ->map(function($tasks) {
                return $tasks->count();
            });
        
        return view('dashboard', compact('stats', 'upcomingTasks', 'tasksByDate'));
    }
}