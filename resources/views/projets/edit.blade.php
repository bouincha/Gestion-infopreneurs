@extends('layouts.app')

@section('title', 'Modifier ' . $projet->titre)

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('projets.index') }}">Projets</a>
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('projets.show', $projet) }}">{{ $projet->titre }}</a>
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">Modifier</span>
@endsection

@section('content')
    <div style="max-width: 820px; margin: 0 auto">
        <div class="page-header">
            <div>
                <h1>Modifier le projet</h1>
                <p>{{ $projet->titre }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('projets.update', $projet) }}">
            @csrf
            @method('PUT')
            @include('projets._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </div>
@endsection
