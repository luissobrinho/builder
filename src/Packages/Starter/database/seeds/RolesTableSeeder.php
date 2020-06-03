<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Role::where('name', 'member')->first()) {
            Role::create([
                'name' => 'member',
                'label' => 'Member',
            ]);
            Role::create([
                'name' => 'admin',
                'label' => 'Admin',
            ]);
            Role::create([
                'name' => 'app',
                'label' => 'App',
            ]);
            Role::create([
                'name' => 'regular',
                'label' => 'Regular',
            ]);
        }
    }
}
