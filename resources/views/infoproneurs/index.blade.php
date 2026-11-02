@extends('layouts.app')

@section('title', 'Infoproneurs')

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">Infoproneurs</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Infoproneurs</h1>
            <p>Gérez les profils, leurs domaines et leurs projets.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('infoproneurs.create') }}" class="btn btn-primary"><x-icon name="plus" /> Nouvel infoproneur</a>
        </div>
    </div>

    <div class="card">
        <form method="GET" class="toolbar" data-auto-submit role="search">
            <div class="search">
                <x-icon name="search" />
                <input type="search" name="q" value="{{ request('q') }}" class="input" placeholder="Rechercher par nom, email ou téléphone…  ( / )" data-search aria-label="Rechercher">
            </div>
            <select name="domaine" class="select select-auto" aria-label="Filtrer par domaine">
                <option value="">Tous les domaines</option>
                @foreach ($domaines as $domaine)
                    <option value="{{ $domaine->id }}" @selected(request('domaine') == $domaine->id)>{{ $domaine->nom }}</option>
                @endforeach
            </select>
            <select name="tri" class="select select-auto" aria-label="Trier">
                <option value="recent" @selected(request('tri', 'recent') === 'recent')>Plus récents</option>
                <option value="nom" @selected(request('tri') === 'nom')>Nom (A → Z)</option>
                <option value="age" @selected(request('tri') === 'age')>Plus jeunes</option>
            </select>
            @if (request()->hasAny(['q', 'domaine', 'tri']))
                <a href="{{ route('infoproneurs.index') }}" class="btn btn-ghost"><x-icon name="x" class="icon-sm" /> Réinitialiser</a>
            @endif
        </form>

        @if ($infoproneurs->isEmpty())
            @if (request()->hasAny(['q', 'domaine']))
                <x-empty-state icon="search" title="Aucun résultat">
                    Aucun infoproneur ne correspond à votre recherche.
                </x-empty-state>
            @else
                <x-empty-state icon="users" title="Aucun infoproneur" :action="route('infoproneurs.create')" action-label="Ajouter un infoproneur">
                    Ajoutez votre premier infoproneur pour commencer.
                </x-empty-state>
            @endif
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Infoproneur</th>
                        <th>Domaine</th>
                        <th class="hide-sm">Âge</th>
                        <th class="hide-sm">Projets</th>
                        <th class="hide-sm">Ajouté le</th>
                        <th><span class="sr-only">Actions</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($infoproneurs as $info)
                        <tr>
                            <td>
                                <a href="{{ route('infoproneurs.show', $info) }}" class="cell-person">
                                    <x-avatar :src="$info->photo_url" :initials="$info->initials" />
                                    <span>
                                        <strong>{{ $info->nom_complet }}</strong>
                                        <span>{{ $info->email ?: 'Né(e) le ' . $info->date_naissance->format('d/m/Y') }}</span>
                                    </span>
                                </a>
                            </td>
                            <td><x-domaine-badge :domaine="$info->domaine" /></td>
                            <td class="hide-sm text-2 nowrap">{{ $info->age }} ans</td>
                            <td class="hide-sm">
                                <a href="{{ route('projets.index', ['infoproneur' => $info->id]) }}" class="badge">{{ $info->projets_count }} projet{{ $info->projets_count > 1 ? 's' : '' }}</a>
                            </td>
                            <td class="hide-sm text-2 nowrap">{{ $info->created_at?->format('d/m/Y') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('infoproneurs.show', $info) }}" class="btn btn-ghost btn-icon" title="Voir" aria-label="Voir {{ $info->nom_complet }}"><x-icon name="eye" /></a>
                                    <a href="{{ route('infoproneurs.edit', $info) }}" class="btn btn-ghost btn-icon" title="Modifier" aria-label="Modifier {{ $info->nom_complet }}"><x-icon name="edit" /></a>
                                    <form action="{{ route('infoproneurs.destroy', $info) }}" method="POST"
                                          data-confirm="« {{ $info->nom_complet }} » et ses {{ $info->projets_count }} projet(s) seront définitivement supprimés.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-icon" title="Supprimer" aria-label="Supprimer {{ $info->nom_complet }}" style="color: var(--danger)"><x-icon name="trash" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $infoproneurs->links() }}
        @endif
    </div>
@endsection
