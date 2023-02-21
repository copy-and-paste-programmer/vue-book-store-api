<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'user_list',
            'user_show',
            'user_create',
            'user_update',
            'user_delete',
            'author_list',
            'author_create',
            'author_update',
            'author_delete',
            'book_list',
            'book_show',
            'book_create',
            'book_update',
            'book_delete',
            'category_list',
            'category_create',
            'category_update',
            'category_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole = Role::create(['name' => 'Super Admin']);
        $adminRole->givePermissionTo($permissions);
        $adminUser = User::find(1);
        $adminUser->assignRole($adminRole);
    }
}
