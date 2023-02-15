<?php

namespace EscolaLms\Tasks\Http\Requests;

use EscolaLms\Tasks\Dtos\CreateTaskNoteDto;
use EscolaLms\Tasks\Models\TaskNote;
use Illuminate\Support\Facades\Gate;

class DeleteTaskNoteRequest extends TaskNoteRequest
{
    public function authorize(): bool
    {
        $taskNote = $this->getTaskNote($this->route('id'));
        $task = $taskNote->task;

        // todo
        return Gate::allows('delete', $taskNote) && $this->isTaskNoteOwner($taskNote) && ($this->isOwner($task) || $this->isAssigned($task));
    }

    public function getId(): ?int
    {
        return $this->route('id');
    }
}
