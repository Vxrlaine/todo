<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'completed',
        'due_date',
        'priority'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed' => 'boolean',
        'due_date' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relationship: Task belongs to a User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get only completed tasks
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('completed', true);
    }

    /**
     * Scope: Get only pending tasks
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('completed', false);
    }

    /**
     * Scope: Get overdue tasks
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->pending()
                    ->whereNotNull('due_date')
                    ->where('due_date', '<', Carbon::now());
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return !$this->completed && 
               $this->due_date && 
               $this->due_date->isPast();
    }

    /**
     * Scope: Get tasks by priority
     */
    public function scopePriority(Builder $query, int $level): Builder
    {
        return $query->where('priority', $level);
    }

    /**
     * Scope: Get tasks due today
     */
    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('due_date', Carbon::today());
    }

    /**
     * Mark task as complete/incomplete
     */
    public function toggleComplete(): self
    {
        $this->completed = !$this->completed;
        $this->save();
        
        return $this;
    }

    /**
     * Set task priority
     */
    public function setPriority(int $priority): self
    {
        if ($priority >= 1 && $priority <= 5) {
            $this->priority = $priority;
            $this->save();
        }
        
        return $this;
    }
}