@props(['statut'])
<span class="badge badge-dot status-{{ $statut }}">{{ \App\Models\Projet::STATUTS[$statut] ?? $statut }}</span>
