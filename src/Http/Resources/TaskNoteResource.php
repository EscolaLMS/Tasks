<?php

namespace EscolaLms\Tasks\Http\Resources;

use EscolaLms\Tasks\Models\TaskNote;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TaskNote
 */
class TaskNoteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'note' => $this->note,
            'user_id' => $this->user_id,
            'task_id' => $this->task_id,
        ];
    }
}
