@extends('layouts.app')

@section('title', 'Projets')

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">Projets</span>
@endsection

@section('content')
    @php
        $filtres = request()->only(['q', 'domaine', 'infoproneur']);
        $filtreInfoproneur = request('infoproneur') ? \App\Models\Infoproneur::find(request('infoproneur')) : null;
    @endphp

    <div class="page-header">
        <div>
            <h1>Projets</h1>
            <p>Suivez l'avancement des projets de vos infoproneurs.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('projets.create', request()->only('infoproneur')) }}" class="btn btn-primary"><x-icon name="plus" /> Nouveau projet</a>
        </div>
    </div>

    <div class="chips" style="margin-bottom: 16px">
        <a href="{{ route('projets.index', $filtres) }}" class="chip {{ request('statut') ? '' : 'active' }}">
            Tous <span class="count">{{ $compteurs->sum() }}</span>
        </a>
        @foreach (\App\Models\Projet::STATUTS as $key => $label)
            <a href="{{ route('projets.index', $filtres + ['statut' => $key]) }}" class="chip {{ request('statut') === $key ? 'active' : '' }}">
                {{ $label }} <span class="count">{{ $compteurs[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    <div class="card" style="margin-bottom: 20px">
        <form method="GET" class="toolbar" style="border-bottom: 0" data-auto-submit role="search">
            @if (request('statut'))<input type="hidden" name="statut" value="{{ request('statut') }}">@endif
            @if ($filtreInfoproneur)<input type="hidden" name="infoproneur" value="{{ $filtreInfoproneur->id }}">@endif
            <div class="search">
                <x-icon name="search" />
                <input type="search" name="q" value="{{ request('q') }}" class="input" placeholder="Rechercher un projet…  ( / )" data-search aria-label="Rechercher">
            </div>
            <select name="domaine" class="select select-auto" aria-label="Filtrer par domaine">
                <option value="">Tous les domaines</option>
                @foreach ($domaines as $domaine)
                    <option value="{{ $domaine->id }}" @selected(request('domaine') == $domaine->id)>{{ $domaine->nom }}</option>
                @endforeach
            </select>
            @if ($filtreInfoproneur)
                <a href="{{ route('projets.index', request()->except('infoproneur')) }}" class="chip active" title="Retirer ce filtre">
                    {{ $filtreInfoproneur->nom_complet }} <x-icon name="x" class="icon-sm" />
                </a>
            @endif
            @if (request()->hasAny(['q', 'domaine', 'statut', 'infoproneur']))
                <a href="{{ route('projets.index') }}" class="btn btn-ghost"><x-icon name="x" class="icon-sm" /> Réinitialiser</a>
            @endif
        </form>
    </div>

    @if ($projets->isEmpty())
        <div class="card">
            @if (request()->hasAny(['q', 'domaine', 'statut', 'infoproneur']))
                <x-empty-state icon="search" title="Aucun projet trouvé">Essayez de modifier vos filtres.</x-empty-state>
            @else
                <x-empty-state icon="folder" title="Aucun projet" :action="route('projets.create')" action-label="Créer un projet">
                    Créez votre premier projet et associez-le à un infoproneur.
                </x-empty-state>
            @endif
        </div>
    @else
        <div class="grid grid-3">
            @foreach ($projets as $projet)
                <article class="card project-card">
                    <div class="card-body">
                        <div class="row" style="justify-content: space-between">
                            <x-status-badge :statut="$projet->statut" />
                            @if ($projet->domaine)<x-domaine-badge :domaine="$projet->domaine" />@endif
                        </div>
                        <h3><a href="{{ route('projets.show', $projet) }}">{{ $projet->titre }}</a></h3>
                        <p class="desc">{{ $projet->description ?: 'Aucune description.' }}</p>
                        <div class="project-meta">
                            <span><x-icon name="calendar" class="icon-sm" /> {{ $projet->date_fin?->format('d/m/Y') ?? 'Sans échéance' }}</span>
                            @if ($projet->budget)<span><x-icon name="wallet" class="icon-sm" /> {{ number_format($projet->budget, 0, ',', ' ') }} DH</span>@endif
                        </div>
                        <div>
                            <div class="row small" style="justify-content: space-between; margin-bottom: 6px">
                                <span class="muted">Avancement</span><strong>{{ $projet->progression }}%</strong>
                            </div>
                            <div class="progress {{ $projet->progression === 100 ? 'is-done' : '' }}"><span style="width: {{ $projet->progression }}%"></span></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('infoproneurs.show', $projet->infoproneur) }}" class="row" style="gap: 8px; color: var(--text-2); min-width: 0">
                            <x-avatar :src="$projet->infoproneur->photo_url" :initials="$projet->infoproneur->initials" :size="28" />
                            <span class="small truncate">{{ $projet->infoproneur->nom_complet }}</span>
                        </a>
                        <div class="actions" style="display: flex; gap: 2px">
                            <a href="{{ route('projets.edit', $projet) }}" class="btn btn-ghost btn-icon" title="Modifier" aria-label="Modifier {{ $projet->titre }}"><x-icon name="edit" class="icon-sm" /></a>
                            <form action="{{ route('projets.destroy', $projet) }}" method="POST" data-confirm="Le projet « {{ $projet->titre }} » sera définitivement supprimé.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-icon" title="Supprimer" aria-label="Supprimer {{ $projet->titre }}" style="color: var(--danger)"><x-icon name="trash" class="icon-sm" /></button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="card mt-4">{{ $projets->links() }}</div>
    @endif
@endsection
