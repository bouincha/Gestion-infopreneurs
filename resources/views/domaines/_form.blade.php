@php $palette = ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#64748b']; @endphp
<div class="field">
    <label for="nom" class="label">Nom <span class="req">*</span></label>
    <input type="text" name="nom" id="nom" value="{{ old('nom', $domaine->nom) }}" maxlength="100" required
           class="input @error('nom') is-invalid @enderror" placeholder="ex. Marketing digital">
    <x-field-error name="nom" />
</div>

<div class="field mt-3">
    <label for="description" class="label">Description</label>
    <textarea name="description" id="description" rows="3" maxlength="1000"
              class="textarea @error('description') is-invalid @enderror" style="min-height: 80px"
              placeholder="Courte description du domaine">{{ old('description', $domaine->description) }}</textarea>
    <x-field-error name="description" />
</div>

<div class="field mt-3">
    <label for="couleur" class="label">Couleur</label>
    <div class="color-field" data-color-field="#domaine-preview">
        <input type="color" value="{{ old('couleur', $domaine->couleur) }}" aria-label="Choisir une couleur">
        <input type="text" name="couleur" id="couleur" value="{{ old('couleur', $domaine->couleur) }}"
               class="input @error('couleur') is-invalid @enderror" maxlength="7" style="width: 110px; font-family: ui-monospace, monospace">
        <span id="domaine-preview" class="badge badge-domaine badge-dot" style="--c: {{ old('couleur', $domaine->couleur) }}">Aperçu</span>
    </div>
    <div class="swatches mt-2">
        @foreach ($palette as $color)
            <button type="button" class="swatch" style="background: {{ $color }}" data-swatch="{{ $color }}" aria-label="Couleur {{ $color }}"></button>
        @endforeach
    </div>
    <x-field-error name="couleur" />
</div>
