<?php

namespace EscolaLms\Tasks\Tests;

use EscolaLms\Tasks\Enums\TaskPermissionEnum;
use EscolaLms\Core\Tests\CreatesUsers as CoreCreateUsers;
use Illuminate\Contracts\Auth\Authenticatable;

trait CreatesUsers
{
    use CoreCreateUsers;

    private function makeUser(array $data = [], bool $create = true): Authenticatable
    {
        return $this->create($data, $create);
    }

    private function makeStudent(array $data = [], bool $create = true): Authenticatable
    {
        $user = $this->create($data, $create);
        $user->givePermissionTo([
            TaskPermissionEnum::CREATE_OWN_TASK,
            TaskPermissionEnum::UPDATE_OWN_TASK,
            TaskPermissionEnum::DELETE_OWN_TASK,
            TaskPermissionEnum::LIST_OWN_TASK,
        ]);

        return $user;
    }
}
