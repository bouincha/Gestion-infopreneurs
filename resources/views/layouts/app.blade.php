<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tableau de bord') · Infopreneurs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
@php
    $user = auth()->user();
    $navigation = [
        ['route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'home', 'label' => 'Tableau de bord'],
        ['route' => 'infoproneurs.index', 'match' => 'infoproneurs.*', 'icon' => 'users', 'label' => 'Infoproneurs', 'count' => $navCounts['infoproneurs'] ?? null],
        ['route' => 'projets.index', 'match' => 'projets.*', 'icon' => 'folder', 'label' => 'Projets', 'count' => $navCounts['projets'] ?? null],
        ['route' => 'domaines.index', 'match' => 'domaines.*', 'icon' => 'tag', 'label' => 'Domaines', 'count' => $navCounts['domaines'] ?? null],
    ];
@endphp

<div class="app">
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-mark"><x-icon name="sparkles" /></span>
            <span>Infopreneurs <small>Espace de gestion</small></span>
        </a>

        <div class="nav-label">Menu</div>
        <nav class="nav">
            @foreach ($navigation as $item)
                <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['match']) ? 'active' : '' }}"
                   @if (request()->routeIs($item['match'])) aria-current="page" @endif>
                    <x-icon :name="$item['icon']" />
                    {{ $item['label'] }}
                    @isset($item['count'])<span class="nav-count">{{ $item['count'] }}</span>@endisset
                </a>
            @endforeach
        </nav>

        <div class="nav-label">Compte</div>
        <nav class="nav">
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <x-icon name="user" /> Mon profil
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="sidebar-user">
                <x-avatar :src="$user->avatar_url" :initials="$user->initials" :size="36" />
                <span class="meta">
                    <strong class="truncate">{{ $user->name ?: 'Administrateur' }}</strong>
                    <span class="truncate">{{ $user->email }}</span>
                </span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link logout-btn"><x-icon name="logout" /> Se déconnecter</button>
            </form>
        </div>
    </aside>
    <div class="backdrop" data-sidebar-toggle></div>

    <div class="main">
        <header class="topbar">
            <button type="button" class="btn btn-ghost btn-icon menu-toggle" data-sidebar-toggle aria-label="Ouvrir le menu">
                <x-icon name="menu" />
            </button>
            <nav class="breadcrumb" aria-label="Fil d'Ariane">
                <a href="{{ route('dashboard') }}">Accueil</a>
                @yield('breadcrumb')
            </nav>
            <div class="spacer"></div>
            <span class="muted small hide-sm">{{ now()->translatedFormat('l j F Y') }}</span>
        </header>

        <main class="content">
            @yield('content')
        </main>
    </div>
</div>

<div class="toasts" aria-live="polite">
    @if (session('success'))
        <div class="toast" role="status">
            <span class="icon-wrap"><x-icon name="check" class="icon-sm" /></span>
            <p>{{ session('success') }}</p>
            <button type="button" aria-label="Fermer"><x-icon name="x" class="icon-sm" /></button>
        </div>
    @endif
    @if (session('error'))
        <div class="toast is-error" role="alert">
            <span class="icon-wrap"><x-icon name="alert" class="icon-sm" /></span>
            <p>{{ session('error') }}</p>
            <button type="button" aria-label="Fermer"><x-icon name="x" class="icon-sm" /></button>
        </div>
    @endif
</div>

<div class="modal" id="confirm-modal" hidden role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="modal-panel">
        <div class="modal-icon"><x-icon name="trash" class="icon-lg" /></div>
        <h3 id="confirm-title" data-confirm-title>Confirmer la suppression</h3>
        <p data-confirm-text></p>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" data-confirm-cancel>Annuler</button>
            <button type="button" class="btn btn-danger" data-confirm-ok>Supprimer</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
@stack('scripts')
</body>
</html>
