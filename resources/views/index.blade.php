@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Bonjour' : ($hour < 18 ? 'Bon après-midi' : 'Bonsoir');
        $maxStatut = max(1, $statuts->max('total'));
        $maxDomaine = max(1, $topDomaines->max('infoproneurs_count'));
        $tones = ['en_attente' => '#9ca3af', 'en_cours' => '#0284c7', 'termine' => '#059669', 'annule' => '#dc2626'];
    @endphp

    <section class="welcome">
        <h1>{{ $greeting }}, {{ $user->name ?: $user->email }}</h1>
        <p>Voici un aperçu de votre activité.</p>
        <div class="welcome-meta">
            <span class="welcome-chip">
                <x-icon name="clock" class="icon-sm" />
                Dernière visite :
                {{ $user->previous_login_at ? $user->previous_login_at->translatedFormat('d M Y à H:i') : 'première connexion' }}
            </span>
            @if ($user->last_login_at)
                <span class="welcome-chip">
                    <x-icon name="activity" class="icon-sm" />
                    En ligne depuis {{ $user->last_login_at->format('H:i') }}
                </span>
            @endif
        </div>
        <div class="welcome-actions">
            <a href="{{ route('infoproneurs.create') }}" class="btn btn-white"><x-icon name="plus" /> Infoproneur</a>
            <a href="{{ route('projets.create') }}" class="btn btn-white"><x-icon name="plus" /> Projet</a>
        </div>
    </section>

    <div class="grid grid-4">
        <div class="card stat-card">
            <span class="stat-icon tone-indigo"><x-icon name="users" /></span>
            <div>
                <div class="stat-label">Infoproneurs</div>
                <div class="stat-value">{{ $stats['infoproneurs'] }}</div>
                <a href="{{ route('infoproneurs.index') }}" class="stat-link">Voir la liste <x-icon name="chevron-right" class="icon-sm" /></a>
            </div>
        </div>
        <div class="card stat-card">
            <span class="stat-icon tone-sky"><x-icon name="folder" /></span>
            <div>
                <div class="stat-label">Projets</div>
                <div class="stat-value">{{ $stats['projets'] }}</div>
                <a href="{{ route('projets.index') }}" class="stat-link">Voir les projets <x-icon name="chevron-right" class="icon-sm" /></a>
            </div>
        </div>
        <div class="card stat-card">
            <span class="stat-icon tone-emerald"><x-icon name="activity" /></span>
            <div>
                <div class="stat-label">Projets en cours</div>
                <div class="stat-value">{{ $stats['en_cours'] }}</div>
                <a href="{{ route('projets.index', ['statut' => 'en_cours']) }}" class="stat-link">Suivre <x-icon name="chevron-right" class="icon-sm" /></a>
            </div>
        </div>
        <div class="card stat-card">
            <span class="stat-icon tone-amber"><x-icon name="wallet" /></span>
            <div>
                <div class="stat-label">Budget total</div>
                <div class="stat-value">{{ number_format($budgetTotal, 0, ',', ' ') }} <span class="small muted">DH</span></div>
                <span class="stat-link muted">{{ $stats['domaines'] }} domaines</span>
            </div>
        </div>
    </div>

    <div class="grid grid-main-side mt-4">
        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Derniers infoproneurs</h2>
                    <p>Les profils ajoutés récemment</p>
                </div>
                <a href="{{ route('infoproneurs.index') }}" class="btn btn-ghost btn-sm">Tout voir <x-icon name="arrow-right" class="icon-sm" /></a>
            </div>
            @if ($recentInfoproneurs->isEmpty())
                <x-empty-state icon="users" title="Aucun infoproneur" :action="route('infoproneurs.create')" action-label="Ajouter un infoproneur">
                    Commencez par ajouter votre premier profil.
                </x-empty-state>
            @else
                <div class="list">
                    @foreach ($recentInfoproneurs as $info)
                        <a href="{{ route('infoproneurs.show', $info) }}" class="list-item">
                            <x-avatar :src="$info->photo_url" :initials="$info->initials" />
                            <span class="meta">
                                <strong class="truncate">{{ $info->nom_complet }}</strong>
                                <span>Ajouté {{ $info->created_at?->diffForHumans() }}</span>
                            </span>
                            <x-domaine-badge :domaine="$info->domaine" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="stack">
            <div class="card">
                <div class="card-header"><h2>Projets par statut</h2></div>
                <div class="card-body bar-list">
                    @foreach ($statuts as $key => $s)
                        <a href="{{ route('projets.index', ['statut' => $key]) }}" class="bar-item" style="color: inherit">
                            <div class="row"><span>{{ $s['label'] }}</span><strong>{{ $s['total'] }}</strong></div>
                            <div class="bar-track"><div class="bar-fill" style="width: {{ $s['total'] / $maxStatut * 100 }}%; background: {{ $tones[$key] }}"></div></div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Domaines populaires</h2>
                    <a href="{{ route('domaines.index') }}" class="btn btn-ghost btn-sm">Gérer</a>
                </div>
                <div class="card-body bar-list">
                    @forelse ($topDomaines as $domaine)
                        <a href="{{ route('infoproneurs.index', ['domaine' => $domaine->id]) }}" class="bar-item" style="color: inherit">
                            <div class="row">
                                <span class="row" style="gap: 8px; margin: 0"><span class="domain-dot" style="--c: {{ $domaine->couleur }}"></span>{{ $domaine->nom }}</span>
                                <strong>{{ $domaine->infoproneurs_count }}</strong>
                            </div>
                            <div class="bar-track"><div class="bar-fill" style="width: {{ $domaine->infoproneurs_count / $maxDomaine * 100 }}%; background: {{ $domaine->couleur }}"></div></div>
                        </a>
                    @empty
                        <p class="muted">Aucun domaine pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <div>
                <h2>Projets récents</h2>
                <p>Les derniers projets créés</p>
            </div>
            <a href="{{ route('projets.index') }}" class="btn btn-ghost btn-sm">Tout voir <x-icon name="arrow-right" class="icon-sm" /></a>
        </div>
        @if ($recentProjets->isEmpty())
            <x-empty-state icon="folder" title="Aucun projet" :action="route('projets.create')" action-label="Créer un projet">
                Créez un projet et associez-le à un infoproneur.
            </x-empty-state>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr><th>Projet</th><th>Infoproneur</th><th>Statut</th><th>Avancement</th><th class="hide-sm">Échéance</th></tr>
                    </thead>
                    <tbody>
                    @foreach ($recentProjets as $projet)
                        <tr>
                            <td><a href="{{ route('projets.show', $projet) }}"><strong>{{ $projet->titre }}</strong></a></td>
                            <td class="text-2">{{ $projet->infoproneur->nom_complet }}</td>
                            <td><x-status-badge :statut="$projet->statut" /></td>
                            <td style="min-width: 120px">
                                <div class="progress {{ $projet->progression === 100 ? 'is-done' : '' }}"><span style="width: {{ $projet->progression }}%"></span></div>
                            </td>
                            <td class="hide-sm text-2 nowrap">{{ $projet->date_fin?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
