<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Infoproneur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_complet',
        'email',
        'telephone',
        'date_naissance',
        'domaine_id',
        'photo',
        'bio',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domaine::class);
    }

    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('nom_complet', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('telephone', 'like', "%{$term}%");
        });
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? route('media', $this->photo) : null;
    }

    public function getInitialsAttribute(): string
    {
        return Str::of($this->nom_complet)->explode(' ')->filter()->take(2)
            ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))->implode('');
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_naissance?->age;
    }
}
