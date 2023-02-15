<?php

namespace EscolaLms\Tasks\Http\Controllers;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Tasks\Http\Requests\CompleteTaskRequest;
use EscolaLms\Tasks\Http\Requests\DeleteTaskRequest;
use EscolaLms\Tasks\Http\Requests\IncompleteTaskRequest;
use EscolaLms\Tasks\Http\Requests\ListTaskRequest;
use EscolaLms\Tasks\Http\Resources\TaskResource;
use EscolaLms\Tasks\Http\Requests\CreateTaskRequest;
use EscolaLms\Tasks\Http\Requests\UpdateTaskRequest;
use EscolaLms\Tasks\Services\TaskService;
use Illuminate\Http\JsonResponse;

// TODO swagger
class TaskController extends EscolaLmsBaseController
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function create(CreateTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->toDto());

        return $this->sendResponseForResource(TaskResource::make($task), __('User task created successfully.'));
    }

    public function update(UpdateTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->update($request->toDto());

        return $this->sendResponseForResource(TaskResource::make($task), __('User task updated successfully.'));
    }

    public function delete(DeleteTaskRequest $request): JsonResponse
    {
        $this->taskService->delete($request->getId());

        return $this->sendSuccess(__('User task deleted successfully.'));
    }

    public function complete(CompleteTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->completeOwn($request->getId());

        return $this->sendResponseForResource(TaskResource::make($task), __('User task complete successfully.'));
    }

    public function incomplete(IncompleteTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->incomplete($request->getId());

        return $this->sendResponseForResource(TaskResource::make($task), __('User task incomplete successfully.'));
    }

    public function findAll(ListTaskRequest $request): JsonResponse
    {
        $collection = $this->taskService->findAllByUser($request->getPage(), $request->getCriteria());

        return $this->sendResponseForResource(TaskResource::collection($collection));
    }
}
