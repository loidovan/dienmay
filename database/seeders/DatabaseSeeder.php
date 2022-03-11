<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Hash;
use Spatie\Permission\Models\Role;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'superadmin']);

        $role2 = Role::create(['name' => 'admin']);

        $role3 = Role::create(['name' => 'user']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Siêu Quản Trị',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Quản Trị',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Người Dùng',
            'email' => 'user@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole($role3);
    }
}
