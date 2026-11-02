<?php

namespace Database\Seeders;

use App\Models\Domaine;
use App\Models\Infoproneur;
use App\Models\Projet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Compte de démonstration : admin@infopreneurs.test / password
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@infopreneurs.test'],
            ['name' => 'Administrateur', 'password' => 'password'],
        );

        $domaines = collect([
            ['nom' => 'Marketing digital', 'couleur' => '#6366f1', 'description' => 'Réseaux sociaux, publicité en ligne, SEO.'],
            ['nom' => 'Développement web', 'couleur' => '#0ea5e9', 'description' => 'Sites, applications et formations techniques.'],
            ['nom' => 'Design', 'couleur' => '#ec4899', 'description' => 'Identité visuelle, UI/UX, création graphique.'],
            ['nom' => 'Finance personnelle', 'couleur' => '#10b981', 'description' => 'Épargne, investissement, budget.'],
            ['nom' => 'Coaching', 'couleur' => '#f59e0b', 'description' => 'Développement personnel et professionnel.'],
        ])->map(fn ($d) => Domaine::firstOrCreate(['nom' => $d['nom']], $d));

        $domaines->each(function (Domaine $domaine) {
            Infoproneur::factory(3)
                ->for($domaine)
                ->has(Projet::factory(2)->state(['domaine_id' => $domaine->id]))
                ->create();
        });
    }
}
