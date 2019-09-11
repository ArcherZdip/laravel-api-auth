<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArcherZdipApiAuthOprateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('apikey.table_name.api_auth_oprate_events');

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_client_id');
            $table->ipAddress('ip_address');
            $table->string('event');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `{$tableName}` comment 'Admin oprate events'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableName = config('apikey.table_name.api_auth_oprate_events');

        Schema::dropIfExists($tableName);
    }
}
