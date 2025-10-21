@extends('personal.app')

@section('content')
    <x-personal::navbar></x-personal::navbar>
    
    <main class="w-full">
        <x-personal::notifikasi></x-personal::notifikasi>
        {{ $slot }}
    </main>

@endsection