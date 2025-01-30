<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        DB::table('departemen')->insert([
            'kode' => '001',
            'nama' => 'Admin',
        ]);
        DB::table('departemen')->insert([
            'kode' => '040',
            'nama' => 'Dosen',
        ]);
        DB::table('role')->insert([
            'akses' => 'admin',
        ]);
        DB::table('role')->insert([
            'akses' => 'user',
        ]);
        DB::table('users')->insert([
            'email' => 'admin@example.com',
            'username' => 'admin',
            'name' => 'Admin',
            'password' => bcrypt('admin'),
            'role_id' => 1,
            'departemen_id' => 1,
            'jenis_kelamin' => '*',
        ]);
        DB::table('users')->insert([
            'email' => 'user@example.com',
            'username' => 'user',
            'name' => 'User',
            'password' => bcrypt('user'),
            'role_id' => 2,
            'departemen_id' => 2,
            'jenis_kelamin' => 'Laki-laki',
        ]);
    }
}
