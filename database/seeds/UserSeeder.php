<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement("DELETE from users");

        $faker = \Faker\Factory::create();

        $data = [];
        for ($i=0; $i < 5; $i++) { 
        	
        	$tmp['name']=$faker->name;
        	$tmp['email']=$faker->email;
        	$tmp['password']=\Hash::make('123456');
        	$tmp['created_at']=date('Y-m-d H:i:s');
        	$tmp['updated_at']=date('Y-m-d H:i:s');
        	$data[]=$tmp;
        }

        \App\User::insert($data);
    }
}
