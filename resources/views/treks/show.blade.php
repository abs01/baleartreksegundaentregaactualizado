@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $trek->name }}</h1>
        <a href="{{ route('trekCRUD.show') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back
        </a>
    </div>

    <x-card-trek :trek="$trek" />
</div>
@endsection
