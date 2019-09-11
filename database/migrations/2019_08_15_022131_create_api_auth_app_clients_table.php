<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiAuthAppClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('apikey.table_name.app_clients');

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('AppName');
            $table->string('appid')->comment('Appid');
            $table->string('secret')->comment('AppSecret');
            $table->boolean('active')->default(1)->comment('Active 0-DeActive 1-Active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('appid');
            $table->index('secret');
        });

        DB::statement("ALTER TABLE `{$tableName}` comment 'AppClients'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableName = config('apikey.table_name.app_clients');

        Schema::dropIfExists($tableName);
    }
}
