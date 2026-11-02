<?php

namespace App\Http\Controllers;

use App\Http\Requests\InfoproneurRequest;
use App\Models\Domaine;
use App\Models\Infoproneur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoproneurController extends Controller
{
    private const SORTS = [
        'recent' => ['created_at', 'desc'],
        'nom' => ['nom_complet', 'asc'],
        'age' => ['date_naissance', 'desc'],
    ];

    public function index(Request $request)
    {
        [$column, $direction] = self::SORTS[$request->query('tri')] ?? self::SORTS['recent'];

        $infoproneurs = Infoproneur::with('domaine')
            ->withCount('projets')
            ->search($request->query('q'))
            ->when($request->query('domaine'), fn ($q, $id) => $q->where('domaine_id', $id))
            ->orderBy($column, $direction)
            ->paginate(8)
            ->withQueryString();

        return view('infoproneurs.index', [
            'infoproneurs' => $infoproneurs,
            'domaines' => Domaine::orderBy('nom')->get(),
        ]);
    }

    public function create()
    {
        return view('infoproneurs.create', [
            'infoproneur' => new Infoproneur,
            'domaines' => Domaine::orderBy('nom')->get(),
        ]);
    }

    public function store(InfoproneurRequest $request)
    {
        $data = $request->safe()->except(['photo', 'supprimer_photo']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $infoproneur = Infoproneur::create($data);

        return redirect()->route('infoproneurs.show', $infoproneur)->with('success', 'Infoproneur ajouté avec succès.');
    }

    public function show(Infoproneur $infoproneur)
    {
        $infoproneur->load(['domaine', 'projets' => fn ($q) => $q->latest()]);

        return view('infoproneurs.show', compact('infoproneur'));
    }

    public function edit(Infoproneur $infoproneur)
    {
        return view('infoproneurs.edit', [
            'infoproneur' => $infoproneur,
            'domaines' => Domaine::orderBy('nom')->get(),
        ]);
    }

    public function update(InfoproneurRequest $request, Infoproneur $infoproneur)
    {
        $data = $request->safe()->except(['photo', 'supprimer_photo']);

        if ($request->hasFile('photo') || $request->boolean('supprimer_photo')) {
            $this->deletePhoto($infoproneur);
            $data['photo'] = $request->hasFile('photo')
                ? $request->file('photo')->store('photos', 'public')
                : null;
        }

        $infoproneur->update($data);

        return redirect()->route('infoproneurs.show', $infoproneur)->with('success', 'Infoproneur modifié avec succès.');
    }

    public function destroy(Infoproneur $infoproneur)
    {
        $this->deletePhoto($infoproneur);
        $infoproneur->delete();

        return redirect()->route('infoproneurs.index')->with('success', 'Infoproneur supprimé avec succès.');
    }

    private function deletePhoto(Infoproneur $infoproneur): void
    {
        if ($infoproneur->photo && Storage::disk('public')->exists($infoproneur->photo)) {
            Storage::disk('public')->delete($infoproneur->photo);
        }
    }
}
