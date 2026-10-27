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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('verification_token')->unique()->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('unit_kerja');
            $table->string('contact_person');
            $table->string('nama');
            $table->string('NPK');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->text('keterangan_approval')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('unit_kerja')->references('id')->on('unit_kerja');
        });

        // Data Submission Table
        Schema::create('data_submission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('nama_tamu');
            $table->timestamps();

            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });

        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_submission_id');
            $table->string('tipe');
            $table->string('file_name');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('data_submission_id')
                  ->references('id')
                  ->on('data_submission')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
        Schema::dropIfExists('data_submission');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('statuses');
    }
};
