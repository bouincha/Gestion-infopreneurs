<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Infoproneur;
use App\Models\Projet;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $statuts = Projet::selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');

        return view('index', [
            'user' => auth()->user(),
            'stats' => [
                'infoproneurs' => Infoproneur::count(),
                'projets' => Projet::count(),
                'domaines' => Domaine::count(),
                'en_cours' => $statuts['en_cours'] ?? 0,
            ],
            'statuts' => collect(Projet::STATUTS)->map(fn ($label, $key) => [
                'label' => $label,
                'total' => (int) ($statuts[$key] ?? 0),
            ]),
            'budgetTotal' => (float) Projet::sum('budget'),
            'recentInfoproneurs' => Infoproneur::with('domaine')->latest()->take(5)->get(),
            'recentProjets' => Projet::with('infoproneur')->latest()->take(5)->get(),
            'topDomaines' => Domaine::withCount('infoproneurs')->orderByDesc('infoproneurs_count')->take(5)->get(),
        ]);
    }
}
