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

        foreach (TaskPermissionEnum::asArray() as $const => $value) {
            Permission::findOrCreate($value, 'api');
        }

        $admin->givePermissionTo(TaskPermissionEnum::asArray());
    }
}
