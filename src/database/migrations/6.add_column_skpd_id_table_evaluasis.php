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
        Schema::table('evaluasis', function (Blueprint $table) {
            $table->foreignId('skpd_id')->nullable();
            $table->string('status_evaluasi')->nullable();
            $table->string('persentase_tindak_lanjut_sebelumnya')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
