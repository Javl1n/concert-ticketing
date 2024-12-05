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
            $table->text('description');
            $table->timestamp('reservation_start');
            $table->timestamp('reservation_end');
            $table->timestamp('concert_start');
            $table->timestamp('concert_end');
            $table->decimal('vip_price', places: 2);
            $table->decimal('general_price', places: 2);
            $table->string('gcash_number');
            $table->string('gcash_name');
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
