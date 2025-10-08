@extends('app')

@section('content')
    <x-navbar></x-navbar>
    
    <main class="w-full">
        <x-notifikasi></x-notifikasi>
        {{ $slot }}
    </main>

@endsection