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
        Schema::create('concerts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->date('reservation_start');   
            $table->date('reservation_end');
            $table->timestamp('concert_start');
            $table->timestamp('concert_end');
            $table->decimal('vip_price', places: 2);
            $table->decimal('general_admission_price', places: 2);
            $table->foreignId('organizer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concerts');
    }
};
