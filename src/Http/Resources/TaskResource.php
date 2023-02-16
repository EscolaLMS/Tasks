<?php

namespace EscolaLms\Tasks\Http\Resources;

use EscolaLms\Tasks\Models\Task;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Task
 */
class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user' => UserResource::make($this->user),
            'created_by' => UserResource::make($this->createdBy),
            'completed_at' => $this->completed_at,
            'related_type' => $this->related_type,
            'related_id' => $this->related_id,
            'has_notes' => $this->taskNotes->count() > 0
        ];
    }
}
