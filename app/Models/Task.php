<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'assignee',
        'created_by',
        'updated_by',
        'status',
    ];

    public static array $allowedStatuses = ['New', 'In Progress', 'Testing', 'Deployed'];
    public function changeStatus($newStatus)
    {
        if (in_array($newStatus, self::$allowedStatuses)) {
            if ($this->status == 'New') {
                $this->status = $newStatus;
                $this->save();
                return true;
            }
            else if ($this->status == 'Deployed') {
                return 'already_deployed';
            }
            else {
                $minutesRemaining = $this->updated_at->diffInMinutes(now());
                if ($minutesRemaining <= 15) {
                    return "not_allowed:$minutesRemaining";
                }
            }
            $this->status = $newStatus;
            $this->save();
            return true;
        }
        return false;
    }


    public function assigneeUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee', 'id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(TaskImages::class);
    }
}
