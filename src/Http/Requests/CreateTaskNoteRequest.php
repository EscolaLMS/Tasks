<?php

namespace EscolaLms\Tasks\Http\Requests;

use EscolaLms\Tasks\Dtos\CreateTaskNoteDto;
use EscolaLms\Tasks\Models\TaskNote;
use Illuminate\Support\Facades\Gate;

class CreateTaskNoteRequest extends TaskNoteRequest
{
    public function authorize(): bool
    {
        $task = $this->getTask($this->input('task_id'));

        return Gate::allows('create', TaskNote::class) && ($this->isCreator($task) || $this->isAssigned($task));
    }

    public function rules(): array
    {
        return [
            'task_id' => ['required', 'integer', 'exists:tasks,id'],
            'note' => ['required', 'string'],
        ];
    }

    public function toDto(): CreateTaskNoteDto
    {
        return CreateTaskNoteDto::instantiateFromRequest($this);
    }
}
