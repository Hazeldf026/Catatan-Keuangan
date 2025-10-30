@extends('group.app')

@section('content')
    <x-group::sidebar></x-group::sidebar>

    <main class="ml-0 sm:ml-64 bg-gray-100 min-h-screen">
        <x-group::notifikasi></x-group::notifikasi>
        <div class="p-7 pt-12">
            {{ $slot }}
        </div>
    </main>

@endsection