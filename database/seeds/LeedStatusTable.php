<?php

use Illuminate\Database\Seeder;

class LeedStatusTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leed_statuses')->insert([
            ['slug' => 'in_work', 'name' => 'В работе'],
            ['slug' => 'offer', 'name' => 'Предложение'],
            ['slug' => 'pressure', 'name' => 'Дожатие'],
            ['slug' => 'converted', 'name' => 'Сконвертирован'],
            ['slug' => 'rejected', 'name' => 'Забракован']
        ]);
    }
}
