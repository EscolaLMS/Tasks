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
            'note' => $this->note,
            'created_by_id' => $this->created_by_id,
            'completed_at' => $this->completed_at,
            'related_type' => $this->related_type,
            'related_id' => $this->related_id,
        ];
    }
}
