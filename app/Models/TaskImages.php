<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'image_path',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
