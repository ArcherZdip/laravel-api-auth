<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiAuthAccessEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_auth_access_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid')->comment('Appid');
            $table->ipAddress('ip_address')->comment('IP');
            $table->text('url');
            $table->text('params')->nullable()->comment('Params');
            $table->string('type', 10)->default('GET')->comment('GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, PATCH, PURGE, TRACE');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `api_auth_access_events` comment 'Api Access Logs'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_auth_access_events');
    }
}
