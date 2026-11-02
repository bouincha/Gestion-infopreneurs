<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Infoproneur;
use App\Models\Projet;

class LandingController extends Controller
{
    public function __invoke()
    {
        return view('landing', [
            'stats' => [
                'infoproneurs' => Infoproneur::count(),
                'projets' => Projet::count(),
                'domaines' => Domaine::count(),
                'budget' => (float) Projet::sum('budget'),
            ],
            'domaines' => Domaine::orderBy('nom')->take(12)->get(['id', 'nom', 'couleur']),
        ]);
    }
}
