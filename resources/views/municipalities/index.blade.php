@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    {{-- <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Users</h1>
        <a href="{{ route('userCRUD.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create New User
        </a>
    </div> --}}

    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ $message }}
        </div>
    @endif

    <div class="grid gap-6">
        @forelse($municipalities as $municipality)
            <x-card-municipality :municipality="$municipality" />
        @empty
            <p class="text-gray-500">No municipalities found</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $municipalities->links() }}
    </div>
</div>
@endsection
