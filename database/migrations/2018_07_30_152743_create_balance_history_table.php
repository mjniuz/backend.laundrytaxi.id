<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('admin_user_id')->nullable();
            $table->string('in_out')->default('in');
            $table->decimal('amount',14);
            $table->decimal('balance_total',14);
            $table->string('message');
            $table->timestamps();
        });

        Schema::table('merchant', function (Blueprint $table) {
            $table->string('real_phone')->default('')->after('phone');
            $table->timestamp('suspended_at')->nullable()->after('address');
            $table->timestamp('unsuspend_schedule')->nullable()->after('suspended_at');
            $table->string('suspend_message')->default('')->after('unsuspend_schedule');
            $table->string('score')->default('0')->after('suspend_message');
            $table->decimal('balance',14)->default(0)->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
