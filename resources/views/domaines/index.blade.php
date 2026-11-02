@extends('layouts.app')

@section('title', 'Domaines')

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">Domaines</span>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1>Domaines</h1>
            <p>Classez vos infoproneurs et projets par domaine d'expertise.</p>
        </div>
        <form method="GET" class="search" role="search" style="flex: 0 1 300px">
            <x-icon name="search" />
            <input type="search" name="q" value="{{ request('q') }}" class="input" placeholder="Rechercher un domaine…" data-search aria-label="Rechercher">
        </form>
    </div>

    <div class="grid grid-main-side">
        <div>
            @if ($domaines->isEmpty())
                <div class="card">
                    <x-empty-state icon="tag" title="{{ request('q') ? 'Aucun résultat' : 'Aucun domaine' }}">
                        {{ request('q') ? 'Aucun domaine ne correspond à votre recherche.' : 'Créez votre premier domaine avec le formulaire.' }}
                    </x-empty-state>
                </div>
            @else
                <div class="grid grid-2">
                    @foreach ($domaines as $domaine)
                        <article class="card domain-card" style="--c: {{ $domaine->couleur }}">
                            <div class="row" style="justify-content: space-between; align-items: flex-start">
                                <div style="min-width: 0">
                                    <h3 class="truncate">{{ $domaine->nom }}</h3>
                                    <p class="desc">{{ $domaine->description ?: 'Aucune description' }}</p>
                                </div>
                                <div class="row" style="gap: 2px">
                                    <a href="{{ route('domaines.edit', $domaine) }}" class="btn btn-ghost btn-icon" title="Modifier" aria-label="Modifier {{ $domaine->nom }}"><x-icon name="edit" class="icon-sm" /></a>
                                    <form action="{{ route('domaines.destroy', $domaine) }}" method="POST"
                                          data-confirm="Le domaine « {{ $domaine->nom }} » sera supprimé. {{ $domaine->infoproneurs_count }} infoproneur(s) et {{ $domaine->projets_count }} projet(s) deviendront « sans domaine ».">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-icon" title="Supprimer" aria-label="Supprimer {{ $domaine->nom }}" style="color: var(--danger)"><x-icon name="trash" class="icon-sm" /></button>
                                    </form>
                                </div>
                            </div>
                            <div class="domain-stats">
                                <a href="{{ route('infoproneurs.index', ['domaine' => $domaine->id]) }}">
                                    <strong>{{ $domaine->infoproneurs_count }}</strong><span>infoproneur(s)</span>
                                </a>
                                <a href="{{ route('projets.index', ['domaine' => $domaine->id]) }}">
                                    <strong>{{ $domaine->projets_count }}</strong><span>projet(s)</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('domaines.store') }}" class="card" style="position: sticky; top: 84px">
            @csrf
            <div class="card-header">
                <div>
                    <h2>Nouveau domaine</h2>
                    <p>Ajoutez un domaine en quelques secondes</p>
                </div>
            </div>
            <div class="card-body">
                @include('domaines._form', ['domaine' => $nouveau])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-block"><x-icon name="plus" /> Ajouter le domaine</button>
            </div>
        </form>
    </div>
@endsection
