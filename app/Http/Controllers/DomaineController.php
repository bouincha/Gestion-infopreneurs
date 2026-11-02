<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomaineRequest;
use App\Models\Domaine;
use Illuminate\Http\Request;

class DomaineController extends Controller
{
    public function index(Request $request)
    {
        $domaines = Domaine::withCount(['infoproneurs', 'projets'])
            ->when($request->query('q'), fn ($q, $term) => $q->where('nom', 'like', "%{$term}%"))
            ->orderBy('nom')
            ->get();

        return view('domaines.index', [
            'domaines' => $domaines,
            'nouveau' => new Domaine(['couleur' => '#6366f1']),
        ]);
    }

    public function store(DomaineRequest $request)
    {
        Domaine::create($request->validated());

        return redirect()->route('domaines.index')->with('success', 'Domaine ajouté avec succès.');
    }

    public function edit(Domaine $domaine)
    {
        return view('domaines.edit', compact('domaine'));
    }

    public function update(DomaineRequest $request, Domaine $domaine)
    {
        $domaine->update($request->validated());

        return redirect()->route('domaines.index')->with('success', 'Domaine modifié avec succès.');
    }

    public function destroy(Domaine $domaine)
    {
        $domaine->delete();

        return redirect()->route('domaines.index')->with('success', 'Domaine supprimé. Les infoproneurs liés sont désormais sans domaine.');
    }
}
