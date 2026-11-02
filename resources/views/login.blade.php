<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion · Infopreneurs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
<div class="auth">
    <aside class="auth-aside">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark"><x-icon name="sparkles" /></span>
            <span>Infopreneurs <small>Espace de gestion</small></span>
        </a>

        <div>
            <h2>Gérez vos infopreneurs et leurs projets, au même endroit.</h2>
            <p>Suivez les profils, organisez-les par domaine et pilotez l'avancement de chaque projet.</p>
            <div class="auth-features">
                <div class="auth-feature"><span class="stat-icon"><x-icon name="users" /></span> Fiches infopreneurs avec photo</div>
                <div class="auth-feature"><span class="stat-icon"><x-icon name="folder" /></span> Projets, statuts et budgets</div>
                <div class="auth-feature"><span class="stat-icon"><x-icon name="tag" /></span> Classement par domaine</div>
            </div>
        </div>

        <p class="small">&copy; {{ date('Y') }} Infopreneurs</p>
    </aside>

    <main class="auth-main">
        <a href="{{ route('home') }}" class="auth-back"><x-icon name="arrow-left" class="icon-sm" /> Retour à l'accueil</a>
        <form method="POST" action="{{ url('/login') }}" class="auth-form" novalidate>
            @csrf
            <h1>Bon retour 👋</h1>
            <p>Connectez-vous pour accéder à votre tableau de bord.</p>

            @if (session('success'))
                <div class="alert alert-success"><x-icon name="check" /> {{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert"><x-icon name="alert" /> {{ $errors->first() }}</div>
            @endif

            <div class="field">
                <label for="email" class="label">Adresse email</label>
                <div class="input-group">
                    <x-icon name="mail" />
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="input @error('email') is-invalid @enderror"
                           placeholder="vous@exemple.com" autocomplete="email" required autofocus>
                </div>
            </div>

            <div class="field">
                <label for="password" class="label">Mot de passe</label>
                <div class="input-group">
                    <x-icon name="lock" />
                    <input type="password" name="password" id="password"
                           class="input @error('password') is-invalid @enderror"
                           placeholder="••••••••" autocomplete="current-password" required>
                    <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Afficher le mot de passe">
                        <span data-eye-open><x-icon name="eye" class="icon-sm" /></span>
                        <span data-eye-closed hidden><x-icon name="eye-off" class="icon-sm" /></span>
                    </button>
                </div>
            </div>

            <div class="row mt-3">
                <label class="checkbox"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                Se connecter <x-icon name="arrow-right" />
            </button>
        </form>
    </main>
</div>
<script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
</body>
</html>
