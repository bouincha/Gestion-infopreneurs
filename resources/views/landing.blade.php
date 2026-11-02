<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infopreneurs · Gérez vos infopreneurs et leurs projets</title>
    <meta name="description" content="Une plateforme simple pour suivre vos infopreneurs, organiser leurs domaines et piloter leurs projets.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="landing">
@php
    $cta = auth()->check()
        ? ['url' => route('dashboard'), 'label' => 'Tableau de bord']
        : ['url' => route('login'), 'label' => 'Se connecter'];

    $features = [
        ['icon' => 'users', 'tone' => 'tone-indigo', 'title' => 'Fiches infopreneurs', 'text' => 'Nom, contact, date de naissance, biographie et photo de profil, réunis sur une seule fiche.'],
        ['icon' => 'folder', 'tone' => 'tone-sky', 'title' => 'Suivi des projets', 'text' => 'Statut, budget en DH, dates de début et de fin, et une barre d\'avancement calculée automatiquement.'],
        ['icon' => 'tag', 'tone' => 'tone-pink', 'title' => 'Domaines colorés', 'text' => 'Classez vos infopreneurs et vos projets par domaine, chacun avec sa propre couleur.'],
        ['icon' => 'search', 'tone' => 'tone-amber', 'title' => 'Recherche et filtres', 'text' => 'Retrouvez n\'importe qui en un instant : recherche par nom, email, téléphone, domaine ou statut.'],
        ['icon' => 'activity', 'tone' => 'tone-emerald', 'title' => 'Tableau de bord', 'text' => 'Une vue d\'ensemble : chiffres clés, répartition des statuts, budget total et activité récente.'],
        ['icon' => 'shield', 'tone' => 'tone-violet', 'title' => 'Accès sécurisé', 'text' => 'Espace protégé par authentification, mots de passe chiffrés et limitation des tentatives de connexion.'],
    ];

    $steps = [
        ['title' => 'Créez vos domaines', 'text' => 'Marketing, design, coaching… définissez les catégories qui structurent votre réseau.'],
        ['title' => 'Ajoutez vos infopreneurs', 'text' => 'Renseignez leur profil, ajoutez une photo et associez-les à un domaine.'],
        ['title' => 'Pilotez leurs projets', 'text' => 'Suivez chaque projet, de « En attente » à « Terminé », avec budget et échéances.'],
    ];
@endphp

<header class="lp-nav">
    <div class="lp-container lp-nav-inner">
        <a href="{{ route('home') }}" class="brand lp-brand">
            <span class="brand-mark"><x-icon name="sparkles" /></span>
            <span>Infopreneurs</span>
        </a>
        <nav class="lp-links" aria-label="Navigation principale">
            <a href="#fonctionnalites">Fonctionnalités</a>
            <a href="#etapes">Comment ça marche</a>
            @if ($domaines->isNotEmpty())<a href="#domaines">Domaines</a>@endif
        </nav>
        <a href="{{ $cta['url'] }}" class="btn btn-primary">{{ $cta['label'] }} <x-icon name="arrow-right" class="icon-sm" /></a>
    </div>
</header>

<main>
    <section class="lp-hero">
        <div class="lp-container lp-hero-inner">
            <div class="lp-hero-text">
                <span class="lp-eyebrow"><x-icon name="sparkles" class="icon-sm" /> Espace de gestion tout-en-un</span>
                <h1>Gérez vos infopreneurs et leurs projets, <span>au même endroit.</span></h1>
                <p>Suivez les profils, organisez-les par domaine et pilotez l'avancement de chaque projet grâce à un tableau de bord clair et moderne.</p>
                <div class="lp-hero-actions">
                    <a href="{{ $cta['url'] }}" class="btn btn-lg lp-btn-white">{{ auth()->check() ? 'Ouvrir mon tableau de bord' : 'Accéder à mon espace' }} <x-icon name="arrow-right" /></a>
                    <a href="#fonctionnalites" class="btn btn-lg lp-btn-outline">Découvrir</a>
                </div>
            </div>

            {{-- Aperçu stylisé du tableau de bord --}}
            <div class="lp-preview" aria-hidden="true">
                <div class="lp-preview-bar"><span></span><span></span><span></span></div>
                <div class="lp-preview-body">
                    <div class="lp-preview-stats">
                        <div><x-icon name="users" class="icon-sm" /><strong>{{ $stats['infoproneurs'] }}</strong><small>Infoproneurs</small></div>
                        <div><x-icon name="folder" class="icon-sm" /><strong>{{ $stats['projets'] }}</strong><small>Projets</small></div>
                        <div><x-icon name="tag" class="icon-sm" /><strong>{{ $stats['domaines'] }}</strong><small>Domaines</small></div>
                    </div>
                    @foreach ([['Lancement formation SEO', 72, 'En cours'], ['Refonte identité visuelle', 100, 'Terminé'], ['Coaching carrière 2026', 35, 'En cours']] as [$titre, $pct, $statut])
                        <div class="lp-preview-row">
                            <div class="row" style="justify-content: space-between">
                                <span>{{ $titre }}</span><small>{{ $statut }}</small>
                            </div>
                            <div class="progress {{ $pct === 100 ? 'is-done' : '' }}"><span style="width: {{ $pct }}%"></span></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="lp-stats">
        <div class="lp-container lp-stats-inner">
            <div><strong>{{ $stats['infoproneurs'] }}</strong><span>infoproneurs suivis</span></div>
            <div><strong>{{ $stats['projets'] }}</strong><span>projets enregistrés</span></div>
            <div><strong>{{ $stats['domaines'] }}</strong><span>domaines d'expertise</span></div>
            <div><strong>{{ number_format($stats['budget'], 0, ',', ' ') }} <small>DH</small></strong><span>de budget géré</span></div>
        </div>
    </section>

    <section class="lp-section" id="fonctionnalites">
        <div class="lp-container">
            <div class="lp-section-head">
                <span class="lp-kicker">Fonctionnalités</span>
                <h2>Tout ce qu'il faut pour gérer votre réseau</h2>
                <p>Des outils simples, pensés pour aller à l'essentiel.</p>
            </div>
            <div class="lp-features">
                @foreach ($features as $feature)
                    <article class="card lp-feature">
                        <span class="stat-icon {{ $feature['tone'] }}"><x-icon :name="$feature['icon']" /></span>
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="lp-section lp-section-alt" id="etapes">
        <div class="lp-container">
            <div class="lp-section-head">
                <span class="lp-kicker">Comment ça marche</span>
                <h2>Prêt en trois étapes</h2>
            </div>
            <ol class="lp-steps">
                @foreach ($steps as $i => $step)
                    <li>
                        <span class="lp-step-num">{{ $i + 1 }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    @if ($domaines->isNotEmpty())
        <section class="lp-section" id="domaines">
            <div class="lp-container">
                <div class="lp-section-head">
                    <span class="lp-kicker">Domaines</span>
                    <h2>Des expertises variées</h2>
                </div>
                <div class="lp-domaines">
                    @foreach ($domaines as $domaine)
                        <x-domaine-badge :domaine="$domaine" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="lp-container">
        <div class="lp-cta">
            <div>
                <h2>Prêt à commencer ?</h2>
                <p>Connectez-vous et retrouvez tous vos infopreneurs en un coup d'œil.</p>
            </div>
            <a href="{{ $cta['url'] }}" class="btn btn-lg lp-btn-white">{{ $cta['label'] }} <x-icon name="arrow-right" /></a>
        </div>
    </section>
</main>

<footer class="lp-footer">
    <div class="lp-container lp-footer-inner">
        <span class="row" style="gap: 8px"><span class="brand-mark lp-brand-mark-sm"><x-icon name="sparkles" class="icon-sm" /></span> Infopreneurs</span>
        <span class="muted small">&copy; {{ date('Y') }} Infopreneurs · Projet scolaire Laravel</span>
    </div>
</footer>
</body>
</html>
