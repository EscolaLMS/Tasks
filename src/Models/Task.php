<?php

namespace EscolaLms\Tasks\Models;

use Carbon\Carbon;
use EscolaLms\Tasks\Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 *
 * Class Tasks
 *
 * @package EscolaLms\Tasks\Models
 *
 * @property int $id
 * @property string $title
 * @property ?string $note
 * @property ?Carbon due_date
 * @property ?Carbon $completed_at
 * @property int $user_id
 * @property int $created_by_id
 * @property ?string $related_type
 * @property ?int $related_id
 *
 * @property User $user
 * @property User $createdBy
 *
 */
class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function related(): MorphTo
    {
        return $this->morphTo('related');
    }

    public function isAssigned(): bool
    {
        return $this->created_by_id !== $this->user_id;
    }

    public function isOwner(): bool
    {
        return $this->created_by_id === $this->user_id;
    }

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }
}
