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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });


        Schema::create('area_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });


        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('area_kerja_id');
            $table->foreign('area_kerja_id')->references('id')->on('area_kerja')->onDelete('cascade');
            $table->string('nama')->unique();
            $table->string('kode')->unique();
            $table->timestamps();
        });


        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('unit_kerja_id')->nullable();
            $table->unsignedBigInteger('area_kerja_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('unit_kerja_id')->references('id')->on('unit_kerja')->onDelete('cascade');
            $table->foreign('area_kerja_id')->references('id')->on('area_kerja')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('unit_kerja');
        Schema::dropIfExists('area_kerja');
    }
};
