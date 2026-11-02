@extends('layouts.app')

@section('title', 'Modifier ' . $domaine->nom)

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('domaines.index') }}">Domaines</a>
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">{{ $domaine->nom }}</span>
@endsection

@section('content')
    <div style="max-width: 560px; margin: 0 auto">
        <div class="page-header">
            <div>
                <h1>Modifier le domaine</h1>
                <p>{{ $domaine->nom }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('domaines.update', $domaine) }}" class="card">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('domaines._form')
            </div>
            <div class="card-footer">
                <a href="{{ route('domaines.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary"><x-icon name="check" /> Enregistrer</button>
            </div>
        </form>
    </div>
@endsection
