@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <x-icon name="alert" />
        <div>
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div>
            <h2>Photo de profil</h2>
            <p>JPG, PNG ou WEBP · 2 Mo maximum</p>
        </div>
    </div>
    <div class="card-body">
        <div class="upload" data-upload>
            <div class="upload-preview">
                @if ($infoproneur->photo_url)
                    <img src="{{ $infoproneur->photo_url }}" alt="">
                @else
                    <x-icon name="image" class="icon-lg" />
                @endif
            </div>
            <div class="upload-body">
                <label for="photo" class="btn btn-secondary btn-sm"><x-icon name="upload" class="icon-sm" /> Choisir une image</label>
                <input type="file" name="photo" id="photo" accept="image/*" class="sr-only">
                <span class="hint" data-upload-name>ou glissez-déposez une image ici</span>
                @if ($infoproneur->photo)
                    <label class="checkbox small"><input type="checkbox" name="supprimer_photo" value="1" data-upload-remove> Supprimer la photo actuelle</label>
                @endif
                <x-field-error name="photo" />
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <div>
            <h2>Informations</h2>
            <p>Les champs marqués <span style="color: var(--danger)">*</span> sont obligatoires</p>
        </div>
    </div>
    <div class="card-body form-grid">
        <div class="field field-full">
            <label for="nom_complet" class="label">Nom complet <span class="req">*</span></label>
            <input type="text" name="nom_complet" id="nom_complet" value="{{ old('nom_complet', $infoproneur->nom_complet) }}"
                   class="input @error('nom_complet') is-invalid @enderror" placeholder="ex. Sara Benali" required maxlength="255" autofocus>
            <x-field-error name="nom_complet" />
        </div>

        <div class="field">
            <label for="email" class="label">Email</label>
            <div class="input-group">
                <x-icon name="mail" />
                <input type="email" name="email" id="email" value="{{ old('email', $infoproneur->email) }}"
                       class="input @error('email') is-invalid @enderror" placeholder="nom@exemple.com">
            </div>
            <x-field-error name="email" />
        </div>

        <div class="field">
            <label for="telephone" class="label">Téléphone</label>
            <div class="input-group">
                <x-icon name="phone" />
                <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', $infoproneur->telephone) }}"
                       class="input @error('telephone') is-invalid @enderror" placeholder="06 12 34 56 78">
            </div>
            <x-field-error name="telephone" />
        </div>

        <div class="field">
            <label for="date_naissance" class="label">Date de naissance <span class="req">*</span></label>
            <input type="date" name="date_naissance" id="date_naissance"
                   value="{{ old('date_naissance', $infoproneur->date_naissance?->format('Y-m-d')) }}"
                   max="{{ now()->subDay()->format('Y-m-d') }}"
                   class="input @error('date_naissance') is-invalid @enderror" required>
            <x-field-error name="date_naissance" />
        </div>

        <div class="field">
            <label for="domaine_id" class="label">Domaine</label>
            <select name="domaine_id" id="domaine_id" class="select @error('domaine_id') is-invalid @enderror">
                <option value="">— Aucun domaine —</option>
                @foreach ($domaines as $domaine)
                    <option value="{{ $domaine->id }}" @selected(old('domaine_id', $infoproneur->domaine_id) == $domaine->id)>{{ $domaine->nom }}</option>
                @endforeach
            </select>
            <span class="hint">Domaine manquant ? <a href="{{ route('domaines.index') }}">Créer un domaine</a></span>
            <x-field-error name="domaine_id" />
        </div>

        <div class="field field-full">
            <label for="bio" class="label">Biographie</label>
            <textarea name="bio" id="bio" rows="4" class="textarea @error('bio') is-invalid @enderror"
                      placeholder="Parcours, expertise, réalisations…" maxlength="2000">{{ old('bio', $infoproneur->bio) }}</textarea>
            <x-field-error name="bio" />
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ $infoproneur->exists ? route('infoproneurs.show', $infoproneur) : route('infoproneurs.index') }}" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary"><x-icon name="check" /> {{ $submitLabel }}</button>
    </div>
</div>
