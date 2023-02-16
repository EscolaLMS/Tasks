<?php

namespace EscolaLms\Tasks\Repositories;

use EscolaLms\Core\Repositories\BaseRepository;
use EscolaLms\Tasks\Models\Task;
use EscolaLms\Tasks\Repositories\Contracts\TaskRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TaskRepository extends BaseRepository implements TaskRepositoryContract
{
    public function model(): string
    {
        return Task::class;
    }

    public function getFieldsSearchable(): array
    {
        return [];
    }

    public function findAllByUserId(int $userId, int $perPage, array $criteria): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['taskNotes', 'user', 'createdBy']);
        $query = $this->applyCriteria($query, $criteria);
        $query = $query->where('user_id', '=', $userId);

        return $query->paginate($perPage);
    }

    public function findAll(int $perPage, array $criteria): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['taskNotes', 'user', 'createdBy']);
        $query = $this->applyCriteria($query, $criteria);

        return $query->paginate($perPage);
    }
}
