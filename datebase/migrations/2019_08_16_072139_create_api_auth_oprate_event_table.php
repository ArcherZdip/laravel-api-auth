<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiAuthOprateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_auth_oprate_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_client_id');
            $table->ipAddress('ip_address');
            $table->string('event');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `api_auth_oprate_events` comment 'Admin oprate events'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_auth_oprate_events');
    }
}
