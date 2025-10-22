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
        Schema::table('unsurs', function (Blueprint $table) {
            if(!Schema::hasColumn('unsurs', 'kategori_unsur_id')){
            $table->foreignId('kategori_unsur_id')->nullable();
            }
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
