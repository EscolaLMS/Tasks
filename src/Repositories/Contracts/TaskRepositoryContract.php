<?php

namespace EscolaLms\Tasks\Repositories\Contracts;

use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TaskRepositoryContract extends BaseRepositoryContract
{
    public function findAllByUserId(int $userId, int $perPage, array $criteria): LengthAwarePaginator;

    public function findAll(int $perPage, array $criteria): LengthAwarePaginator;
}
