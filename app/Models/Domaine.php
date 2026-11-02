<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domaine extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'couleur',
    ];

    protected static function booted(): void
    {
        // Same effect as the nullOnDelete() foreign keys, also on SQLite where
        // a foreign key added by ALTER TABLE is not enforced.
        static::deleting(function (Domaine $domaine) {
            $domaine->infoproneurs()->update(['domaine_id' => null]);
            $domaine->projets()->update(['domaine_id' => null]);
        });
    }

    public function infoproneurs(): HasMany
    {
        return $this->hasMany(Infoproneur::class);
    }

    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class);
    }
}
