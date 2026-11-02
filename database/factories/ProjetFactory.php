<?php

namespace Database\Factories;

use App\Models\Infoproneur;
use App\Models\Projet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Projet>
 */
class ProjetFactory extends Factory
{
    public function definition(): array
    {
        $debut = fake()->dateTimeBetween('-6 months', '+1 month');

        return [
            'titre' => fake()->randomElement([
                'Lancement d\'une formation en ligne', 'Création d\'une chaîne YouTube', 'Refonte du site vitrine',
                'Campagne publicitaire Instagram', 'Écriture d\'un e-book', 'Programme de coaching 8 semaines',
                'Boutique en ligne de templates', 'Podcast hebdomadaire', 'Atelier en présentiel à Casablanca',
                'Newsletter payante', 'Masterclass gratuite', 'Application mobile d\'accompagnement',
                'Pack de ressources Notion', 'Webinaire de lancement', 'Communauté privée sur Discord',
            ]),
            'description' => fake()->randomElement([
                'Préparation du contenu, enregistrement des vidéos et mise en ligne sur la plateforme.',
                'Objectif : toucher un nouveau public et augmenter le nombre d\'abonnés de 30 %.',
                'Étude de marché, création de l\'offre puis stratégie de communication sur les réseaux sociaux.',
                'Projet mené en partenariat avec une agence locale, avec un suivi mensuel des résultats.',
                'Mise en place d\'un tunnel de vente complet : page de capture, e-mails et page de paiement.',
            ]),
            'infoproneur_id' => Infoproneur::factory(),
            'statut' => fake()->randomElement(array_keys(Projet::STATUTS)),
            'budget' => fake()->randomFloat(2, 1000, 80000),
            'date_debut' => $debut->format('Y-m-d'),
            'date_fin' => (clone $debut)->modify('+'.fake()->numberBetween(20, 200).' days')->format('Y-m-d'),
        ];
    }
}
