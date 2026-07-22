@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

    <x-home.hero />

    <x-home.methods :methods="$methods" />

    <x-home.featured :products="$products" />

@endsection