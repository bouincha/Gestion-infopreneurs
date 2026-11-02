@extends('layouts.app')

@section('title', 'Nouveau projet')

@section('breadcrumb')
    <x-icon name="chevron-right" class="icon-sm" /><a href="{{ route('projets.index') }}">Projets</a>
    <x-icon name="chevron-right" class="icon-sm" /><span class="current">Nouveau</span>
@endsection

@section('content')
    <div style="max-width: 820px; margin: 0 auto">
        <div class="page-header">
            <div>
                <h1>Nouveau projet</h1>
                <p>Décrivez le projet et associez-le à un infoproneur.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('projets.store') }}">
            @csrf
            @include('projets._form', ['submitLabel' => 'Créer le projet'])
        </form>
    </div>
@endsection
