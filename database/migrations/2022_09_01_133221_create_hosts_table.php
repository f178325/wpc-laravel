<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosts', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('type');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('hosts');
    }
};
