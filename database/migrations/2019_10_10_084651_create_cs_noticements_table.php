<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsNoticementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_noticements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id');
            $table->char('title', 255)->collation('utf8mb4_unicode_ci');
            $table->text('noticement_text')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('created_by')->unsigned();
            $table->timestamps();
            $table->char('visiblity', 255)->collation('utf8mb4_unicode_ci')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cs_noticements');
    }
}
