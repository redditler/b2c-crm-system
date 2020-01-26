<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cs_topics')->insert([
	      'id'					=> 1,
	      'topic_name'			=> 'Тестовая тема разговора',
	      'topic_description'	=> 'Тестовое описание темы',
	      'created_at'			=> date("Y-m-d H:i:s", time()),
	      'updated_at'			=> null
	    ]);
    }
}
