@extends('layouts.app')

@section('title', 'Mon profil')

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">Mon profil</span>
@endsection

@section('content')
    <div style="max-width: 820px; margin: 0 auto" class="stack">
        <div class="page-header" style="margin-bottom: 0">
            <div>
                <h1>Mon profil</h1>
                <p>Gérez vos informations personnelles et votre sécurité.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="card">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div>
                    <h2>Informations personnelles</h2>
                    <p>Votre nom et votre email apparaissent dans l'application</p>
                </div>
            </div>
            <div class="card-body">
                <div class="upload" data-upload style="margin-bottom: 20px">
                    <div class="upload-preview">
                        @if ($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="">
                        @else
                            <span class="avatar" style="--size: 76px">{{ $user->initials }}</span>
                        @endif
                    </div>
                    <div class="upload-body">
                        <label for="avatar" class="btn btn-secondary btn-sm"><x-icon name="upload" class="icon-sm" /> Changer l'avatar</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="sr-only">
                        <span class="hint" data-upload-name>JPG ou PNG, 2 Mo maximum</span>
                        <x-field-error name="avatar" />
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="name" class="label">Nom <span class="req">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                               class="input @error('name') is-invalid @enderror">
                        <x-field-error name="name" />
                    </div>
                    <div class="field">
                        <label for="email" class="label">Email <span class="req">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="input @error('email') is-invalid @enderror">
                        <x-field-error name="email" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><x-icon name="check" /> Enregistrer</button>
            </div>
        </form>

        <form method="POST" action="{{ route('profile.password') }}" class="card">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div>
                    <h2>Mot de passe</h2>
                    <p>Utilisez au moins 8 caractères</p>
                </div>
                <span class="stat-icon tone-indigo" style="width: 38px; height: 38px"><x-icon name="shield" class="icon-sm" /></span>
            </div>
            <div class="card-body form-grid">
                <div class="field field-full">
                    <label for="current_password" class="label">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" autocomplete="current-password" required
                           class="input @error('current_password', 'password') is-invalid @enderror" style="max-width: 380px">
                    <x-field-error name="current_password" bag="password" />
                </div>
                <div class="field">
                    <label for="password" class="label">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" autocomplete="new-password" required minlength="8"
                           class="input @error('password', 'password') is-invalid @enderror">
                    <x-field-error name="password" bag="password" />
                </div>
                <div class="field">
                    <label for="password_confirmation" class="label">Confirmation</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" required class="input">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><x-icon name="lock" /> Changer le mot de passe</button>
            </div>
        </form>

        <div class="card card-body">
            <div class="row row-wrap">
                <span class="stat-icon tone-sky" style="width: 38px; height: 38px"><x-icon name="clock" class="icon-sm" /></span>
                <div>
                    <strong>Activité du compte</strong>
                    <p class="muted small">
                        Connexion actuelle : {{ $user->last_login_at?->translatedFormat('j F Y à H:i') ?? '—' }}
                        · Précédente : {{ $user->previous_login_at?->translatedFormat('j F Y à H:i') ?? 'aucune' }}
                        · Membre depuis {{ $user->created_at?->translatedFormat('F Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
