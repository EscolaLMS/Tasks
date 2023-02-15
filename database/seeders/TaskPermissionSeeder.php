<?php

namespace EscolaLms\Tasks\Database\Seeders;

use EscolaLms\Tasks\Enums\TaskPermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TaskPermissionSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::findOrCreate('admin', 'api');
        $student = Role::findOrCreate('student', 'api');

        foreach (TaskPermissionEnum::asArray() as $const => $value) {
            Permission::findOrCreate($value, 'api');
        }

        $admin->givePermissionTo(TaskPermissionEnum::asArray());
        $student->givePermissionTo([
            TaskPermissionEnum::CREATE_OWN_TASK,
            TaskPermissionEnum::UPDATE_OWN_TASK,
            TaskPermissionEnum::DELETE_OWN_TASK,
            TaskPermissionEnum::LIST_OWN_TASK,
            TaskPermissionEnum::CREATE_TASK_NOTE,
            TaskPermissionEnum::UPDATE_TASK_NOTE,
            TaskPermissionEnum::DELETE_TASK_NOTE,
            TaskPermissionEnum::LIST_TASK_NOTE,
        ]);
    }
}
