<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'contact' => '9000000000',
            'password' => Hash::make('admin123'),
            'image' => Null,
            'role' => 'admin',
            'permission'=> Null,
            'status'=>'approved',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
