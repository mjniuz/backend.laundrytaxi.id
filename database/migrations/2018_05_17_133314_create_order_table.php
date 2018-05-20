<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_no')->unique();
            $table->integer('user_id');
            $table->integer('merchant_id');
            $table->string('full_name');
            $table->string('phone');
            $table->decimal('estimate_weight');
            $table->string('package');
            $table->string('lat');
            $table->string('lng');
            $table->text('full_address');
            $table->text('address_note');
            $table->text('note');

            $table->string('status');
            $table->timestamp('pickup_at')->nullable();
            $table->timestamp('process_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->string('rating');
            $table->text('success_comment')->nullable();

            $table->decimal('actual_count')->default(0);
            $table->decimal('actual_weight')->default(0);
            $table->decimal('delivery_fee', 14)->default(0);
            $table->decimal('grand_total', 14)->default(0);
            $table->timestamps();
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
