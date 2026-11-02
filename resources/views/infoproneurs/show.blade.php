@extends('layouts.app')

@section('title', $infoproneur->nom_complet)

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('infoproneurs.index') }}">Infoproneurs</a>
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">{{ $infoproneur->nom_complet }}</span>
@endsection

@section('content')
    <div class="card card-body">
        <div class="profile-head">
            <x-avatar :src="$infoproneur->photo_url" :initials="$infoproneur->initials" :size="88" />
            <div style="flex: 1; min-width: 220px">
                <h1>{{ $infoproneur->nom_complet }}</h1>
                <div class="row row-wrap">
                    <x-domaine-badge :domaine="$infoproneur->domaine" />
                    <span class="muted small">{{ $infoproneur->age }} ans · ajouté {{ $infoproneur->created_at?->diffForHumans() }}</span>
                </div>
            </div>
            <div class="page-actions">
                <a href="{{ route('infoproneurs.edit', $infoproneur) }}" class="btn btn-secondary"><x-icon name="edit" /> Modifier</a>
                <form action="{{ route('infoproneurs.destroy', $infoproneur) }}" method="POST"
                      data-confirm="« {{ $infoproneur->nom_complet }} » et ses {{ $infoproneur->projets->count() }} projet(s) seront définitivement supprimés.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-soft"><x-icon name="trash" /> Supprimer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-side-main mt-4">
        <div class="stack">
            <div class="card">
                <div class="card-header"><h2>Coordonnées</h2></div>
                <dl class="card-body dl" style="margin: 0">
                    <div class="dl-item">
                        <x-icon name="mail" />
                        <div><dt>Email</dt><dd>@if ($infoproneur->email)<a href="mailto:{{ $infoproneur->email }}">{{ $infoproneur->email }}</a>@else<span class="muted">Non renseigné</span>@endif</dd></div>
                    </div>
                    <div class="dl-item">
                        <x-icon name="phone" />
                        <div><dt>Téléphone</dt><dd>@if ($infoproneur->telephone)<a href="tel:{{ $infoproneur->telephone }}">{{ $infoproneur->telephone }}</a>@else<span class="muted">Non renseigné</span>@endif</dd></div>
                    </div>
                    <div class="dl-item">
                        <x-icon name="cake" />
                        <div><dt>Date de naissance</dt><dd>{{ $infoproneur->date_naissance->translatedFormat('j F Y') }}</dd></div>
                    </div>
                    <div class="dl-item">
                        <x-icon name="folder" />
                        <div><dt>Budget cumulé des projets</dt><dd>{{ number_format($infoproneur->projets->sum('budget'), 2, ',', ' ') }} DH</dd></div>
                    </div>
                </dl>
            </div>

            <div class="card">
                <div class="card-header"><h2>Biographie</h2></div>
                <div class="card-body">
                    @if ($infoproneur->bio)
                        <p class="prose">{{ $infoproneur->bio }}</p>
                    @else
                        <p class="muted">Aucune biographie. <a href="{{ route('infoproneurs.edit', $infoproneur) }}">Ajouter</a></p>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Projets</h2>
                    <p>{{ $infoproneur->projets->count() }} projet(s) associé(s)</p>
                </div>
                <a href="{{ route('projets.create', ['infoproneur' => $infoproneur->id]) }}" class="btn btn-primary btn-sm"><x-icon name="plus" class="icon-sm" /> Nouveau projet</a>
            </div>
            @if ($infoproneur->projets->isEmpty())
                <x-empty-state icon="folder" title="Aucun projet" :action="route('projets.create', ['infoproneur' => $infoproneur->id])" action-label="Créer un projet">
                    Cet infoproneur n'a encore aucun projet.
                </x-empty-state>
            @else
                <div class="list">
                    @foreach ($infoproneur->projets as $projet)
                        <a href="{{ route('projets.show', $projet) }}" class="list-item">
                            <span class="stat-icon tone-indigo" style="width: 38px; height: 38px"><x-icon name="folder" class="icon-sm" /></span>
                            <span class="meta">
                                <strong class="truncate">{{ $projet->titre }}</strong>
                                <span>
                                    @if ($projet->date_debut){{ $projet->date_debut->format('d/m/Y') }} → {{ $projet->date_fin?->format('d/m/Y') ?? '…' }}@else Dates non définies @endif
                                    @if ($projet->budget) · {{ number_format($projet->budget, 0, ',', ' ') }} DH @endif
                                </span>
                            </span>
                            <x-status-badge :statut="$projet->statut" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
