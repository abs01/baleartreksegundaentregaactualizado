@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Treks</h1>

    </div>

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ $message }}
        </div>
    @endif

    <div class="grid gap-6">
        @forelse($treks as $trek)
            <x-card-trek :trek="$trek" />
        @empty
            <p class="text-gray-500">No treks found</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $treks->links() }}
    </div>
</div>
@endsection
