<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUcDETUBcAcDO extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('date_employment')->nullable();
        });

        Schema::table('user_branches', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->timestamp('date_opening')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('date_employment');
        });

        Schema::table('user_branches', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('date_opening');
        });
    }
}
