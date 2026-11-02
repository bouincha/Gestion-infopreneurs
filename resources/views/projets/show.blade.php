@extends('layouts.app')

@section('title', $projet->titre)

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('projets.index') }}">Projets</a>
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">{{ $projet->titre }}</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <div class="row row-wrap" style="margin-bottom: 10px">
                <x-status-badge :statut="$projet->statut" />
                @if ($projet->domaine)<x-domaine-badge :domaine="$projet->domaine" />@endif
            </div>
            <h1>{{ $projet->titre }}</h1>
            <p>Créé {{ $projet->created_at?->diffForHumans() }} · modifié {{ $projet->updated_at?->diffForHumans() }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('projets.edit', $projet) }}" class="btn btn-secondary"><x-icon name="edit" /> Modifier</a>
            <form action="{{ route('projets.destroy', $projet) }}" method="POST" data-confirm="Le projet « {{ $projet->titre }} » sera définitivement supprimé.">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger-soft"><x-icon name="trash" /> Supprimer</button>
            </form>
        </div>
    </div>

    <div class="grid grid-main-side">
        <div class="stack">
            <div class="card">
                <div class="card-header"><h2>Avancement</h2><strong>{{ $projet->progression }}%</strong></div>
                <div class="card-body">
                    <div class="progress {{ $projet->progression === 100 ? 'is-done' : '' }}" style="height: 10px"><span style="width: {{ $projet->progression }}%"></span></div>
                    <div class="row small muted mt-2" style="justify-content: space-between">
                        <span>{{ $projet->date_debut?->translatedFormat('j M Y') ?? 'Début non défini' }}</span>
                        <span>{{ $projet->date_fin?->translatedFormat('j M Y') ?? 'Fin non définie' }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h2>Description</h2></div>
                <div class="card-body">
                    @if ($projet->description)
                        <p class="prose">{{ $projet->description }}</p>
                    @else
                        <p class="muted">Aucune description. <a href="{{ route('projets.edit', $projet) }}">Ajouter</a></p>
                    @endif
                </div>
            </div>
        </div>

        <div class="stack">
            <div class="card">
                <div class="card-header"><h2>Infoproneur</h2></div>
                <a href="{{ route('infoproneurs.show', $projet->infoproneur) }}" class="list-item" style="border: 0">
                    <x-avatar :src="$projet->infoproneur->photo_url" :initials="$projet->infoproneur->initials" :size="44" />
                    <span class="meta">
                        <strong>{{ $projet->infoproneur->nom_complet }}</strong>
                        <span>{{ $projet->infoproneur->domaine?->nom ?? 'Sans domaine' }}</span>
                    </span>
                    <x-icon name="chevron-right" class="icon-sm" />
                </a>
            </div>

            <div class="card">
                <div class="card-header"><h2>Informations</h2></div>
                <dl class="card-body dl" style="margin: 0">
                    <div class="dl-item">
                        <x-icon name="wallet" />
                        <div><dt>Budget</dt><dd>{{ $projet->budget !== null ? number_format($projet->budget, 2, ',', ' ') . ' DH' : '—' }}</dd></div>
                    </div>
                    <div class="dl-item">
                        <x-icon name="calendar" />
                        <div><dt>Période</dt><dd>
                            @if ($projet->date_debut && $projet->date_fin)
                                {{ $projet->date_debut->format('d/m/Y') }} → {{ $projet->date_fin->format('d/m/Y') }}
                                <span class="muted small">({{ $projet->date_debut->diffInDays($projet->date_fin) }} jours)</span>
                            @else
                                {{ $projet->date_debut?->format('d/m/Y') ?? '—' }}
                            @endif
                        </dd></div>
                    </div>
                    @if ($projet->date_fin && ! in_array($projet->statut, ['termine', 'annule']))
                        <div class="dl-item">
                            <x-icon name="clock" />
                            <div><dt>Échéance</dt><dd>
                                @if ($projet->date_fin->lt(today()))
                                    <span style="color: var(--danger)">En retard de {{ $projet->date_fin->diffInDays(today()) }} jour(s)</span>
                                @elseif ($projet->date_fin->isToday())
                                    <span style="color: var(--warning, #f59e0b)">Aujourd'hui</span>
                                @else
                                    Dans {{ today()->diffInDays($projet->date_fin) }} jour(s)
                                @endif
                            </dd></div>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection
