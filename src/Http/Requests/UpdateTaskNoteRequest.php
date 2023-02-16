<?php

namespace EscolaLms\Tasks\Http\Requests;

use EscolaLms\Tasks\Dtos\CreateTaskNoteDto;
use EscolaLms\Tasks\Dtos\UpdateTaskNoteDto;
use EscolaLms\Tasks\Models\TaskNote;
use Illuminate\Support\Facades\Gate;

class UpdateTaskNoteRequest extends CreateTaskNoteRequest
{
    public function authorize(): bool
    {
        $taskNote = $this->getTaskNote(
            $this->route('id')
        );
        $task = $taskNote->task;

        return Gate::allows('update', $taskNote) && $this->isTaskNoteOwner($taskNote) && ($this->isOwner($task) || $this->isAssigned($task));
    }

    public function toDto(): UpdateTaskNoteDto
    {
        return UpdateTaskNoteDto::instantiateFromRequest($this);
    }
}
