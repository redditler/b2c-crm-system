<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LeedType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leeds', function (Blueprint $table) {
            $table->integer('leed_type_id')->default(1)->after('label_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leeds', function (Blueprint $table) {
            $table->dropColumn('leed_type_id');
        });
    }
}
