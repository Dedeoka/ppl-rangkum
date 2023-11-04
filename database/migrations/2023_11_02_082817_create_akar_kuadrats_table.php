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
        Schema::create('akar_kuadrats', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('metode');
            $table->float('bilangan', 30, 10);
            $table->float('akar_kuadrat', 30, 10);
            $table->float('waktu', 12, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akar_kuadrats');
    }
};
