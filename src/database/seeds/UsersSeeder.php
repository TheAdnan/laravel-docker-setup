<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Adnan Kicin',
                'email' => 'adnankicin92@gmail.com',
                'password' => 'kicin',
                'email_verified_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Stefan Poslovi',
                'email' => 'steve@jobs.io',
                'password' => 'kicin',
                'email_verified_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Bill Kapije',
                'email' => 'bill@gates.org',
                'password' => 'kicin',
                'email_verified_at' => \Carbon\Carbon::now(),
            ],
        ];

        foreach ($users as $user) {
            $user['password'] = Hash::make($user['password']);
            DB::table('users')->insert($user);
        }
    }
}
