@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <x-icon name="alert" />
        <div>
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    </div>
@endif

@if ($infoproneurs->isEmpty())
    <div class="card">
        <x-empty-state icon="users" title="Aucun infoproneur" :action="route('infoproneurs.create')" action-label="Ajouter un infoproneur">
            Un projet doit être associé à un infoproneur. Ajoutez-en un d'abord.
        </x-empty-state>
    </div>
@else
    <div class="card">
        <div class="card-header">
            <div>
                <h2>Détails du projet</h2>
                <p>Les champs marqués <span style="color: var(--danger)">*</span> sont obligatoires</p>
            </div>
        </div>
        <div class="card-body form-grid">
            <div class="field field-full">
                <label for="titre" class="label">Titre <span class="req">*</span></label>
                <input type="text" name="titre" id="titre" value="{{ old('titre', $projet->titre) }}"
                       class="input @error('titre') is-invalid @enderror" placeholder="ex. Lancement d'une formation en ligne" required maxlength="255" autofocus>
                <x-field-error name="titre" />
            </div>

            <div class="field">
                <label for="infoproneur_id" class="label">Infoproneur <span class="req">*</span></label>
                <select name="infoproneur_id" id="infoproneur_id" class="select @error('infoproneur_id') is-invalid @enderror" required>
                    <option value="">— Choisir —</option>
                    @foreach ($infoproneurs as $info)
                        <option value="{{ $info->id }}" @selected(old('infoproneur_id', $projet->infoproneur_id) == $info->id)>{{ $info->nom_complet }}</option>
                    @endforeach
                </select>
                <x-field-error name="infoproneur_id" />
            </div>

            <div class="field">
                <label for="domaine_id" class="label">Domaine</label>
                <select name="domaine_id" id="domaine_id" class="select @error('domaine_id') is-invalid @enderror">
                    <option value="">— Aucun domaine —</option>
                    @foreach ($domaines as $domaine)
                        <option value="{{ $domaine->id }}" @selected(old('domaine_id', $projet->domaine_id) == $domaine->id)>{{ $domaine->nom }}</option>
                    @endforeach
                </select>
                <x-field-error name="domaine_id" />
            </div>

            <div class="field">
                <label for="statut" class="label">Statut <span class="req">*</span></label>
                <select name="statut" id="statut" class="select @error('statut') is-invalid @enderror" required>
                    @foreach (\App\Models\Projet::STATUTS as $key => $label)
                        <option value="{{ $key }}" @selected(old('statut', $projet->statut) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-field-error name="statut" />
            </div>

            <div class="field">
                <label for="budget" class="label">Budget</label>
                <div class="input-group">
                    <x-icon name="wallet" />
                    <input type="number" name="budget" id="budget" value="{{ old('budget', $projet->budget) }}" min="0" step="0.01"
                           class="input @error('budget') is-invalid @enderror" placeholder="0,00" style="padding-right: 44px">
                    <span class="addon">DH</span>
                </div>
                <x-field-error name="budget" />
            </div>

            <div class="field">
                <label for="date_debut" class="label">Date de début</label>
                <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut', $projet->date_debut?->format('Y-m-d')) }}"
                       class="input @error('date_debut') is-invalid @enderror">
                <x-field-error name="date_debut" />
            </div>

            <div class="field">
                <label for="date_fin" class="label">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin', $projet->date_fin?->format('Y-m-d')) }}"
                       class="input @error('date_fin') is-invalid @enderror">
                <x-field-error name="date_fin" />
            </div>

            <div class="field field-full">
                <label for="description" class="label">Description</label>
                <textarea name="description" id="description" rows="5" class="textarea @error('description') is-invalid @enderror"
                          placeholder="Objectifs, livrables, public cible…" maxlength="5000">{{ old('description', $projet->description) }}</textarea>
                <x-field-error name="description" />
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ $projet->exists ? route('projets.show', $projet) : url()->previous(route('projets.index')) }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><x-icon name="check" /> {{ $submitLabel }}</button>
        </div>
    </div>
@endif
