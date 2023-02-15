<?php

namespace EscolaLms\Tasks\Services;

use Carbon\Carbon;
use EscolaLms\Tasks\Dtos\CreateTaskDto;
use EscolaLms\Tasks\Dtos\PageDto;
use EscolaLms\Tasks\Dtos\CriteriaDto;
use EscolaLms\Tasks\Dtos\UpdateTaskDto;
use EscolaLms\Tasks\Events\TaskAssignedEvent;
use EscolaLms\Tasks\Events\TaskCompleteRequestEvent;
use EscolaLms\Tasks\Events\TaskCompleteUserConfirmationEvent;
use EscolaLms\Tasks\Events\TaskDeletedEvent;
use EscolaLms\Tasks\Events\TaskUpdatedEvent;
use EscolaLms\Tasks\Models\Task;
use EscolaLms\Tasks\Repositories\Contracts\TaskRepositoryContract;
use EscolaLms\Tasks\Services\Contracts\TaskServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService implements TaskServiceContract
{
    private TaskRepositoryContract $taskRepositoryContract;

    public function __construct(TaskRepositoryContract $taskRepositoryContract)
    {
        $this->taskRepositoryContract = $taskRepositoryContract;
    }

    public function create(CreateTaskDto $dto): Task
    {
        /** @var Task $task */
        $task = $this->taskRepositoryContract->create($dto->toArray());

        if ($task->isAssigned()) {
            event(new TaskAssignedEvent($task->user, $task));
        }

        return $task;
    }

    public function update(UpdateTaskDto $dto): Task
    {
        /** @var Task $task */
        $task = $this->taskRepositoryContract->update($dto->toArray(), $dto->getId());

        if ($task->isAssigned()) {
            event(new TaskUpdatedEvent($task->user, $task));
        }

        return $task;
    }

    public function delete(int $id): void
    {
        /** @var Task $task */
        $task = $this->taskRepositoryContract->find($id);

        if ($task->isAssigned()) {
            event(new TaskDeletedEvent($task->user, $task));
        }

        $this->taskRepositoryContract->delete($id);
    }

    public function completeOwn(int $id): Task
    {
        /** @var Task $task */
        $task = $this->taskRepositoryContract->find($id);

        if ($task->isOwner()) {
            $task->completed_at = Carbon::now();
            $task = $this->taskRepositoryContract->update($task->toArray(), $task->getKey());
        } else {
            event(new TaskCompleteRequestEvent($task->createdBy, $task));
        }

        return $task;
    }

    public function complete(int $id): Task
    {
        /** @var Task $task */
        $task = $this->taskRepositoryContract->find($id);

        $task->completed_at = Carbon::now();
        $this->taskRepositoryContract->update($task->toArray(), $task->getKey());

        if (!$task->isOwner()) {
            event(new TaskCompleteUserConfirmationEvent($task->user, $task));
        }

        return $task;
    }

    public function incomplete(int $id): Task
    {
        /** @var Task $task */
        $task = $this->taskRepositoryContract->find($id);

        $task->completed_at = null;
        $this->taskRepositoryContract->update($task->toArray(), $task->getKey());

        return $task;
    }

    public function findAllByUser(PageDto $pageDto, CriteriaDto $criteriaDto): LengthAwarePaginator
    {
        return $this->taskRepositoryContract->findAllByUserId(
            auth()->id(),
            $pageDto->getPerPage(),
            $criteriaDto->toArray()
        );
    }

    public function findAll(PageDto $pageDto, CriteriaDto $criteriaDto): LengthAwarePaginator
    {
        return $this->taskRepositoryContract->findAll(
            $pageDto->getPerPage(),
            $criteriaDto->toArray()
        );
    }
}
