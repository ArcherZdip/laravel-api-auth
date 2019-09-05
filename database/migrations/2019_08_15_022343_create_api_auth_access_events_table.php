<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiAuthAccessEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('apikey.table_name.api_auth_access_events');

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('appid')->comment('Appid');
            $table->ipAddress('ip_address')->comment('IP');
            $table->text('url');
            $table->json('params')->nullable()->comment('Params');
            $table->string('type', 10)->default('GET')->comment('GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, PATCH, PURGE, TRACE');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `{$tableName}` comment 'Api Access Logs'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableName = config('apikey.table_name.api_auth_access_events');

        Schema::dropIfExists($tableName);
    }
}
