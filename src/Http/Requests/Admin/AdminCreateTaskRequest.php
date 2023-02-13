<?php

namespace EscolaLms\Tasks\Http\Requests\Admin;

use EscolaLms\Tasks\Dtos\AdminCreateTaskDto;
use EscolaLms\Tasks\Http\Requests\TaskRequest;
use EscolaLms\Tasks\Models\Task;
use Illuminate\Support\Facades\Gate;

class AdminCreateTaskRequest extends TaskRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Task::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'note' => ['string'],
            'related_type' => ['string'],
            'related_id' => ['integer'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'due_date' => ['date', 'after_or_equal:now'],
        ];
    }

    public function toDto(): AdminCreateTaskDto
    {
        return AdminCreateTaskDto::instantiateFromRequest($this);
    }
}
