<?php

namespace EscolaLms\Tasks\Http\Resources;

use EscolaLms\Tasks\Models\Task;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *      schema="TaskResource",
 *      required={"title", "user", "created_by"},
 *      @OA\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="related_type",
 *          description="related_type",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="related_id",
 *          description="related_id",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="user",
 *          ref="#/components/schemas/UserResource"
 *      ),
 *      @OA\Property(
 *          property="created_by",
 *          ref="#/components/schemas/UserResource"
 *      ),
 *      @OA\Property(
 *          property="due_date",
 *          description="due_date",
 *          type="string",
 *          format="date-time"
 *      ),
 *     @OA\Property(
 *          property="completed_at",
 *          description="completed_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *     @OA\Property(
 *          property="has_notes",
 *          description="has_notes",
 *          type="boolean"
 *      ),
 * )
 *
 */

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
            'due_date' => $this->due_date,
            'completed_at' => $this->completed_at,
            'related_type' => $this->related_type,
            'related_id' => $this->related_id,
            'has_notes' => $this->taskNotes->count() > 0
        ];
    }
}
