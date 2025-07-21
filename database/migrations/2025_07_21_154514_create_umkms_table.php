<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->text('description');
            $table->text('full_description')->nullable();
            $table->string('image');
            $table->string('location');
            $table->string('address');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('owner');
            $table->string('operating_hours');
            $table->year('established')->nullable();
            $table->integer('employees')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};
