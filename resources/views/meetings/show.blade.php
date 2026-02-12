@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $meeting->trek->name ?? 'N/A' }}</h1>
        <a href="{{ route('meetings.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back
        </a>
    </div>

    <x-card-meeting :meeting="$meeting" />
</div>
@endsection

