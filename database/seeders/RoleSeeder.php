<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private const PERMISSIONS = [
        [
            'name' => 'Account',
            'code' => 'ACCOUNT'
        ],
        [
            'name' => 'Device',
            'code' => 'DEVICE'
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \DB::transaction(function () {
            foreach (self::PERMISSIONS as $index => $item) {
                $permission = Permission::query()->firstOrNew(['code' => $item['code']]);
                $permission->name = $item['name'];
                $permission->sort = $index;
                $permission->save();
            }

            $role = Role::query()->firstOrNew(['name' => 'Admin']);
            $role->save();

            $permissions = Permission::query()->get();

            $role->permissions()->sync($permissions);
        });
    }
}
