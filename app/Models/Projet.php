<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Projet extends Model
{
    use HasFactory;

    public const STATUTS = [
        'en_attente' => 'En attente',
        'en_cours' => 'En cours',
        'termine' => 'Terminé',
        'annule' => 'Annulé',
    ];

    protected $fillable = [
        'titre',
        'description',
        'infoproneur_id',
        'domaine_id',
        'statut',
        'budget',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'budget' => 'decimal:2',
    ];

    public function infoproneur(): BelongsTo
    {
        return $this->belongsTo(Infoproneur::class);
    }

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domaine::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $term ? $query->where('titre', 'like', "%{$term}%") : $query;
    }

    public function getStatutLabelAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    /** Progress shown on cards, derived from the status and the planned dates. */
    public function getProgressionAttribute(): int
    {
        if ($this->statut === 'termine') {
            return 100;
        }
        if ($this->statut !== 'en_cours' || ! $this->date_debut || ! $this->date_fin) {
            return $this->statut === 'en_cours' ? 50 : 0;
        }
        $total = max(1, $this->date_debut->diffInDays($this->date_fin));
        $elapsed = $this->date_debut->diffInDays(now(), false);

        return (int) max(0, min(99, round($elapsed / $total * 100)));
    }
}
