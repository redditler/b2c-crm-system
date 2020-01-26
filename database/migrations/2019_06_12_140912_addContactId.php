<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leeds', function (Blueprint $table) {
            $table->integer('contact_id')->after('leed_phone')	;

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
            $table->dropColumn('contact_id');
        });
    }
}
