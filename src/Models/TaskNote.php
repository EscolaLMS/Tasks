<?php

namespace EscolaLms\Tasks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * Class TaskNote
 *
 * @package EscolaLms\Tasks\Models
 *
 * @property int $id
 * @property string $note
 * @property int $user_id
 * @property int $task_id
 *
 * @property User $user
 * @property Task $task
 *
 */
class TaskNote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
