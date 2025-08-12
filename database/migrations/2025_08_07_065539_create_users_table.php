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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('phone')->unique();
            $table->enum('gender', ['nam', 'nu'])->nullable();
            $table->date('dob')->nullable(); // ngày sinh
            $table->string('password');
            $table->string('address');
            $table->enum('role', ['khach_hang', 'nhan_vien', 'admin'])->default('khach_hang');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
