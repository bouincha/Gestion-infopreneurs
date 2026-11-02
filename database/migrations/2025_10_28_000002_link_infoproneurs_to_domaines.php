<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace the free-text `domaine` column by a foreign key to `domaines`,
     * converting every existing value into a domaine row.
     */
    public function up(): void
    {
        Schema::table('infoproneurs', function (Blueprint $table) {
            $table->foreignId('domaine_id')->nullable()->after('date_naissance')
                ->constrained('domaines')->nullOnDelete();
            $table->string('email')->nullable()->after('nom_complet');
            $table->string('telephone', 30)->nullable()->after('email');
            $table->text('bio')->nullable()->after('photo');
        });

        $palette = ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'];
        $names = DB::table('infoproneurs')->whereNotNull('domaine')->distinct()->pluck('domaine');

        foreach ($names as $i => $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }
            $id = DB::table('domaines')->where('nom', $name)->value('id')
                ?? DB::table('domaines')->insertGetId([
                    'nom' => $name,
                    'couleur' => $palette[$i % count($palette)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            DB::table('infoproneurs')->where('domaine', $name)->update(['domaine_id' => $id]);
        }

        Schema::table('infoproneurs', function (Blueprint $table) {
            $table->dropColumn('domaine');
        });
    }

    public function down(): void
    {
        Schema::table('infoproneurs', function (Blueprint $table) {
            $table->string('domaine')->nullable()->after('date_naissance');
        });

        foreach (DB::table('domaines')->get() as $domaine) {
            DB::table('infoproneurs')->where('domaine_id', $domaine->id)->update(['domaine' => $domaine->nom]);
        }

        Schema::table('infoproneurs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('domaine_id');
            $table->dropColumn(['email', 'telephone', 'bio']);
        });
    }
};
