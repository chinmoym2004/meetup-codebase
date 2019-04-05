<?php

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement("DELETE from posts");

        $faker = \Faker\Factory::create();

        $data = [];
        for ($i=0; $i < 1000; $i++) { 
        	$temp['title']=$faker->sentence;
	    	$temp['body']=$faker->realText;
	    	$temp['user_id']=rand(1,5);
	    	$temp['created_at']=date('Y-m-d H:i:s');
	    	$temp['updated_at']=date('Y-m-d H:i:s');
	    	$data[]=$temp;
        }

        \App\Post::insert($data);


    }
}
