<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeResponseTimeColumnToApiAuthAccessEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('apikey.table_name.api_auth_access_events');

        Schema::table($tableName, function(Blueprint $table) {
            $table->decimal('response_time', 12, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableName = config('apikey.table_name.api_auth_access_events');

        Schema::table($tableName, function(Blueprint $table) {
            $table->decimal('response_time', 8, 4)->change();
        });
    }
}
