<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->foreignId('infoproneur_id')->constrained('infoproneurs')->cascadeOnDelete();
            $table->foreignId('domaine_id')->nullable()->constrained('domaines')->nullOnDelete();
            $table->string('statut', 20)->default('en_attente');
            $table->decimal('budget', 12, 2)->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
