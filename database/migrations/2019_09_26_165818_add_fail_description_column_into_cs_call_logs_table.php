<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFailDescriptionColumnIntoCsCallLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cs_call_logs', function (Blueprint $table) {
            $table->text('fail_description')->nullable(true)->collation('utf8mb4_unicode_ci');
            $table->tinyInteger('is_removed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cs_call_logs', function (Blueprint $table) {
            $table->dropColumn('fail_description');
            $table->dropColumn('is_removed');
        });
    }
}
