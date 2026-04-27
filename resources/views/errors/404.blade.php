@extends('layouts.app')

@section('content')
<div style="text-align:center; padding: 4rem;">
    <h1 style="font-size:3rem; color:#8b5cf6;">404</h1>
    <h2>Página no encontrada</h2>
    <p>La página que buscas no existe o fue movida.</p>
    <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top:2rem;">Ir al inicio</a>
</div>
@endsection
