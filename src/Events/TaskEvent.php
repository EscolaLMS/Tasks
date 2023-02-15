<?php

namespace EscolaLms\Tasks\Events;

use EscolaLms\Tasks\Models\Task;
use EscolaLms\Tasks\Models\User;

abstract class TaskEvent
{
    private User $user;

    private Task $task;

    public function __construct(User $user, Task $task)
    {
        $this->user = $user;
        $this->task = $task;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTask(): Task
    {
        return $this->task;
    }
}
