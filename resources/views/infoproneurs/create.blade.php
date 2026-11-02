@extends('layouts.app')

@section('title', 'Nouvel infoproneur')

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('infoproneurs.index') }}">Infoproneurs</a>
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">Nouveau</span>
@endsection

@section('content')
    <div style="max-width: 820px; margin: 0 auto">
        <div class="page-header">
            <div>
                <h1>Nouvel infoproneur</h1>
                <p>Renseignez les informations du profil.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('infoproneurs.store') }}" enctype="multipart/form-data">
            @csrf
            @include('infoproneurs._form', ['submitLabel' => 'Ajouter'])
        </form>
    </div>
@endsection
