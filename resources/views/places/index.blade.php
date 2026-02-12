@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Lugares de interés</h1>

    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ $message }}
        </div>
    @endif

    <div class="grid gap-6">
        @forelse($places as $place)
            <x-card-place :place="$place" />
        @empty
            <p class="text-gray-500">No places found</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $places->links() }}
    </div>
</div>
@endsection
