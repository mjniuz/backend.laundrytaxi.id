<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('activated')->nullable()->after('remember_token');
            $table->string('activate_code')->default('')->after('activated');
            $table->timestamp('activate_code_expired')->nullable()->after('activate_code');
        });
        Schema::table('order', function (Blueprint $table) {
            $table->string('promo')->default('')->after('package');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
