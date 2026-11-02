<?php

namespace Database\Factories;

use App\Models\Domaine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infoproneur>
 */
class InfoproneurFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom_complet' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake()->phoneNumber(),
            'date_naissance' => fake()->dateTimeBetween('-55 years', '-18 years')->format('Y-m-d'),
            'domaine_id' => Domaine::factory(),
            'bio' => fake()->randomElement([
                'Passionné(e) par la transmission, accompagne les débutants depuis plus de cinq ans.',
                'Créateur(rice) de contenu et formateur(rice), aide les entrepreneurs à développer leur activité en ligne.',
                'Ancien(ne) salarié(e) reconverti(e) dans l\'entrepreneuriat digital, partage son expérience à travers des formations.',
                'Spécialiste reconnu(e) dans son domaine, intervient régulièrement lors de conférences et d\'ateliers.',
            ]),
        ];
    }
}
