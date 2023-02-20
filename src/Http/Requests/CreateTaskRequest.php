<?php

namespace EscolaLms\Tasks\Http\Requests;

use EscolaLms\Tasks\Dtos\CreateTaskDto;
use EscolaLms\Tasks\Models\Task;
use Illuminate\Support\Facades\Gate;


/**
 * @OA\Schema(
 *      schema="TaskCreateRequest",
 *      required={"title"},
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
 *          property="due_date",
 *          description="due_date",
 *          type="string",
 *          format="date-time"
 *      ),
 * )
 *
 */
class CreateTaskRequest extends TaskRequest
{
    public function authorize(): bool
    {
        return Gate::allows('createOwn', Task::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'related_type' => ['string'],
            'related_id' => ['integer'],
            'due_date' => ['date', 'after_or_equal:now'],
        ];
    }

    public function toDto(): CreateTaskDto
    {
        return CreateTaskDto::instantiateFromRequest($this);
    }
}
