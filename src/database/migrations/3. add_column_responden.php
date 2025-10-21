<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('respons', function (Blueprint $table) {
            $table->enum('disabilitas',['disabilitas','non_disabilitas'])->default('non_disabilitas');
            $table->string('jenis_disabilitas')->nullable();
            $table->tinyInteger('u12')->default(0);
            $table->tinyInteger('u13')->default(0);
            $table->tinyInteger('u14')->default(0);
            $table->tinyInteger('u15')->default(0);
            $table->tinyInteger('u16')->default(0);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
