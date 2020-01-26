<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadTypeReceive extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leed_types')->insert([
            ['title' => 'Стандартный лид', 'slug' => 'normalLead'],
            ['title' => 'Промо. лид', 'slug' => 'promoLead'],
        ]);
        DB::table('leed_receives')->insert([
            ['title' => 'Интернет', 'slug' => 'internet'],
            ['title' => 'Колл-центр', 'slug' => 'callCenterManager'],
            ['title' => 'Посетитель', 'slug' => 'visitor'],
        ]);
    }
}
