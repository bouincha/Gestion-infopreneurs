<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjetRequest;
use App\Models\Domaine;
use App\Models\Infoproneur;
use App\Models\Projet;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    public function index(Request $request)
    {
        $projets = Projet::with(['infoproneur', 'domaine'])
            ->search($request->query('q'))
            ->when($request->query('statut'), fn ($q, $statut) => $q->where('statut', $statut))
            ->when($request->query('domaine'), fn ($q, $id) => $q->where('domaine_id', $id))
            ->when($request->query('infoproneur'), fn ($q, $id) => $q->where('infoproneur_id', $id))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('projets.index', [
            'projets' => $projets,
            'domaines' => Domaine::orderBy('nom')->get(),
            'compteurs' => Projet::selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut'),
        ]);
    }

    public function create(Request $request)
    {
        $projet = new Projet([
            'statut' => 'en_attente',
            'infoproneur_id' => $request->query('infoproneur'),
        ]);

        return view('projets.create', $this->formData($projet));
    }

    public function store(ProjetRequest $request)
    {
        $projet = Projet::create($request->validated());

        return redirect()->route('projets.show', $projet)->with('success', 'Projet créé avec succès.');
    }

    public function show(Projet $projet)
    {
        $projet->load(['infoproneur.domaine', 'domaine']);

        return view('projets.show', compact('projet'));
    }

    public function edit(Projet $projet)
    {
        return view('projets.edit', $this->formData($projet));
    }

    public function update(ProjetRequest $request, Projet $projet)
    {
        $projet->update($request->validated());

        return redirect()->route('projets.show', $projet)->with('success', 'Projet modifié avec succès.');
    }

    public function destroy(Projet $projet)
    {
        $projet->delete();

        return redirect()->route('projets.index')->with('success', 'Projet supprimé avec succès.');
    }

    private function formData(Projet $projet): array
    {
        return [
            'projet' => $projet,
            'infoproneurs' => Infoproneur::orderBy('nom_complet')->get(['id', 'nom_complet']),
            'domaines' => Domaine::orderBy('nom')->get(),
        ];
    }
}
