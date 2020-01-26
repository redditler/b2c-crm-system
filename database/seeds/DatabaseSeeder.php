<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            'slug' => 'regionManager',
            'name' => 'Региональный мененджер',
        ]);
        $this->call(TopicsTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
    }
}
