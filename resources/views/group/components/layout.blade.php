@extends('group.app')

@section('content')
    <x-group::sidebar></x-group::sidebar>

    <main class="ml-0 sm:ml-64 bg-gray-100 min-h-screen">
        <div class="p-6">
            {{ $slot }}
        </div>
    </main>

@endsection