@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $trek->name }}</h1>

    </div>

    <x-card-trek :trek="$trek" />
</div>
@endsection

