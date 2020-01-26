<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RejectedLead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leeds', function (Blueprint $table) {
            $table->boolean('rejected_lead')->default(false)->after('label_id');
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
            $table->dropColumn('rejected_lead');
        });
    }
}
