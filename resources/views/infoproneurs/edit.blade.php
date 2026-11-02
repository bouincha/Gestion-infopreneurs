@extends('layouts.app')

@section('title', 'Modifier ' . $infoproneur->nom_complet)

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('infoproneurs.index') }}">Infoproneurs</a>
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('infoproneurs.show', $infoproneur) }}">{{ $infoproneur->nom_complet }}</a>
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">Modifier</span>
@endsection

@section('content')
    <div style="max-width: 820px; margin: 0 auto">
        <div class="page-header">
            <div>
                <h1>Modifier l'infoproneur</h1>
                <p>Mettez à jour le profil de {{ $infoproneur->nom_complet }}.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('infoproneurs.update', $infoproneur) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('infoproneurs._form', ['submitLabel' => 'Enregistrer'])
        </form>
    </div>
@endsection
