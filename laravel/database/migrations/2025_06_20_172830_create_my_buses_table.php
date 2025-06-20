<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('my_buses', function (Blueprint $table) {
            $table->id();
            $table->string('cl');
            $table->string('lc');
            $table->string('lt');
            $table->string('sl');
            $table->string('tl');
            $table->string('tp');
            $table->string('ts');
            $table->unsignedBigInteger('user_id'); 
            $table->string('name_bus');
            $table->boolean('status')->default(true); 
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['cl', 'user_id']); 
            $table->index('status');
            $table->index('cl', 'my_buses_cl_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_buses');
    }
};
