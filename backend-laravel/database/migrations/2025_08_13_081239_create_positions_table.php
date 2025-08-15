<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id'); // new FK column
            $table->unsignedBigInteger('device_id')->nullable(); // optional, for reference
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('speed', 8, 3)->nullable();
            $table->timestamp('device_time')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();

            $table->foreign('driver_id')
                ->references('id')
                ->on('drivers')
                ->onDelete('cascade');

            $table->index('device_id'); // optional for lookup
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
