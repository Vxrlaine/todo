@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <div class="text-red-400">
            <i class="fas fa-calendar-alt mr-2"></i>
            <span id="current-date">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Statistik -->
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400">Total Tugas</p>
                    <h3 class="text-2xl font-bold">{{ $stats['total'] }}</h3>
                </div>
                <i class="fas fa-tasks text-red-500 text-xl"></i>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400">Selesai</p>
                    <h3 class="text-2xl font-bold">{{ $stats['completed'] }}</h3>
                </div>
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400">Belum Selesai</p>
                    <h3 class="text-2xl font-bold">{{ $stats['pending'] }}</h3>
                </div>
                <i class="fas fa-clock text-yellow-500 text-xl"></i>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 shadow-lg border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400">Kedaluwarsa</p>
                    <h3 class="text-2xl font-bold">{{ $stats['overdue'] }}</h3>
                </div>
                <i class="fas fa-exclamation-triangle text-purple-500 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kalender -->
        <div class="lg:col-span-2 bg-gray-800 rounded-lg p-6 shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Kalender Tugas</h2>
                <div class="flex space-x-2">
                    <button id="prev-month" class="p-2 hover:bg-gray-700 rounded">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="next-month" class="p-2 hover:bg-gray-700 rounded">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div id="calendar" class="calendar"></div>
        </div>

        <!-- Tugas Mendatang -->
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
            <h2 class="text-xl font-bold mb-6">Tugas Mendatang</h2>
            @if($upcomingTasks->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-check-circle text-3xl mb-2"></i>
                    <p>Tidak ada tugas mendatang</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($upcomingTasks as $task)
                        <div class="task-card bg-gray-750 hover:bg-gray-700 rounded-lg p-4 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium @if($task->completed) line-through @endif">{{ $task->title }}</h3>
                                    <p class="text-sm text-gray-400 mt-1">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $task->due_date->format('M d, Y') }}
                                        @if($task->isOverdue())
                                            <span class="text-red-400 ml-2">(Terlambat)</span>
                                        @endif
                                    </p>
                                </div>
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1 rounded-full hover:bg-gray-600">
                                        @if($task->completed)
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        @else
                                            <i class="far fa-circle text-gray-400"></i>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .calendar {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
        color: #e63946;
    }
    
    .calendar-body {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }
    
    .calendar-day {
        aspect-ratio: 1;
        padding: 5px;
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }
    
    .calendar-day:hover {
        background-color: #2d2d2d;
    }
    
    .day-number {
        font-weight: bold;
        margin-bottom: 2px;
    }
    
    .today {
        background-color: #e63946;
        color: white;
    }
    
    .has-tasks {
        border: 1px solid #e63946;
    }
    
    .task-dot {
        width: 6px;
        height: 6px;
        background-color: #e63946;
        border-radius: 50%;
        margin: 1px auto;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                          "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        const dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        
        let currentDate = new Date();
        let tasksByDate = @json($tasksByDate);
        
        function renderCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendarEl.innerHTML = '';
            
            // Header hari
            const headerEl = document.createElement('div');
            headerEl.className = 'calendar-header';
            dayNames.forEach(day => {
                const dayEl = document.createElement('div');
                dayEl.textContent = day.substring(0, 3);
                headerEl.appendChild(dayEl);
            });
            calendarEl.appendChild(headerEl);
            
            // Body kalender
            const bodyEl = document.createElement('div');
            bodyEl.className = 'calendar-body';
            
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
            const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
            
            // Hari dari bulan sebelumnya
            for (let i = 0; i < firstDay; i++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day text-gray-500';
                bodyEl.appendChild(dayEl);
            }
            
            // Hari di bulan ini
            const today = new Date();
            for (let i = 1; i <= daysInMonth; i++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day bg-gray-750';
                
                const dateStr = `${currentDate.getFullYear()}-${(currentDate.getMonth()+1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
                const hasTasks = tasksByDate[dateStr] && tasksByDate[dateStr].length > 0;
                
                if (hasTasks) {
                    dayEl.classList.add('has-tasks');
                }
                
                if (i === today.getDate() && currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) {
                    dayEl.classList.add('today');
                }
                
                const dayNumberEl = document.createElement('div');
                dayNumberEl.className = 'day-number';
                dayNumberEl.textContent = i;
                dayEl.appendChild(dayNumberEl);
                
                if (hasTasks) {
                    const taskDotEl = document.createElement('div');
                    taskDotEl.className = 'task-dot';
                    dayEl.appendChild(taskDotEl);
                    
                    dayEl.addEventListener('click', () => {
                        alert(`Anda memiliki ${tasksByDate[dateStr].length} tugas pada tanggal ${i} ${monthNames[currentDate.getMonth()]}`);
                    });
                }
                
                bodyEl.appendChild(dayEl);
            }
            
            calendarEl.appendChild(bodyEl);
            
            // Update judul bulan dan tahun
            document.getElementById('current-date').textContent = 
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        }
        
        document.getElementById('prev-month').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
        
        document.getElementById('next-month').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
        
        renderCalendar();
    });
</script>
@endsection