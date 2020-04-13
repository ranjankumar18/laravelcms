<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email','ranjan@cmstest.com')->first();

        if(!$user){
        	User::create([
        	'name' => 'Rajan Baitha',
        	'email' => 'ranjan@cmstest.com',
        	'password' => Hash::make('password'),
            'role' => 'admin'
        	]);
        }
    }
}
