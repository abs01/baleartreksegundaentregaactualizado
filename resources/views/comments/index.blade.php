@extends('layouts.app')
@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Comments</h1>
        </div>
        @if ($message = Session::get('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ $message }}
            </div>
        @endif
        <div class="grid gap-6">
            @forelse($comments as $comment)
                <x-card-comments :comment="$comment" />
            @empty
                <p class="text-gray-500">No comments found</p>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $comments->links() }}
        </div>
    </div>
@endsection 