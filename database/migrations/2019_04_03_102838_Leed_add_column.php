<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LeedAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leeds', function (Blueprint $table) {
            $table->integer('cm_id')->nullable()->after('comment');
            $table->text('cm_comment')->nullable()->after('cm_id');
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
            $table->dropColumn('cm_id');
            $table->dropColumn('cm_comment');
        });
    }
}
