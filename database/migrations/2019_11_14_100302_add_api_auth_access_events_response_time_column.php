<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiAuthAccessEventsResponseTimeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('apikey.table_name.api_auth_access_events');

        Schema::create($tableName, function(Blueprint $table) {
            $table->string('response_time')->default('')->comment('响应时间')->after('params');
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

        Schema::create($tableName, function(Blueprint $table) {
            $table->dropColumn('response_time');
        });
    }
}
