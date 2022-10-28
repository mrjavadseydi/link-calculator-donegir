<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_out_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->integer('msg_id');
            $table->tinyInteger('status')->default(0);
            $table->foreignIdFor(\App\Models\Account::class);
            $table->foreignIdFor(\App\Models\Wallet::class);
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
        Schema::dropIfExists('pay_out_requests');
    }
};
