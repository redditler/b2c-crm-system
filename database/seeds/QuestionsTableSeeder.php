<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cs_questions')->insert([
	      'id'					=> 1,
	      'topic'				=> 1,
	      'question_text'		=> 'Тестовый текст вопроса',
	      'variants'			=> '[{"id": "1568180239292", "link": "-2", "title": "Успешное завершение разговора"}, {"id": "1568180240299", "link": "-1", "title": "Неуспешное завершение разговора"}]',
	      'parent_id'			=> -1,
	      'type'				=> 1,
	      'created_at'			=> date("Y-m-d H:i:s", time()),
	      'updated_at'			=> null
	    ]);
    }
}
