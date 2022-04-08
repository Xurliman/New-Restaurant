<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $floors = [1, 2];
            $cabins = range(1, 12);
            
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Basket::class);
            $table->boolean('isActive')->default(true);
            $table->in_array($floors)->default(1);
            $table->in_array($cabins)->default(1);
            $table->integer('people')->default(1);
            $table->date('date');
            $table->time('time');
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
        Schema::dropIfExists('orders');
    }
}
