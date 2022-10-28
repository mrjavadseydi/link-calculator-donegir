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
        Schema::create('sponser_links', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Sponser::class);
            $table->foreignIdFor(\App\Models\Channel::class);
            $table->string('link');
            $table->integer('usage')->default(0);
            $table->integer('calc')->default(0);
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
        Schema::dropIfExists('sponser_links');
    }
};
