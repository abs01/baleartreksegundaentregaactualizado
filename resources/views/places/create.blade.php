@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-gray-100 px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Create Place of Interest</h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('places.store') }}" method="POST">
                    @csrf
                    
                    <!-- Place Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                            Place of Interest Name *
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" 
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                        
                    <!-- Place Types (Scrollable) -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Place Types *
                        </label>
                        <div class="border rounded p-4 bg-gray-50 max-h-60 overflow-y-auto">
                            @forelse($placeTypes as $placeType)
                                <div class="flex items-start mb-2">
                                    <input type="radio" 
                                           name="place_type_id" 
                                           value="{{ $placeType->id }}" 
                                           id="type_{{ $placeType->id }}"
                                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                           {{ old('place_type_id') == $placeType->id ? 'checked' : '' }}
                                           required>
                                    <label for="type_{{ $placeType->id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                        <span class="font-medium">{{ $placeType->name }}</span>
                                        @if($placeType->description)
                                            <span class="block text-gray-500 text-xs mt-1">{{ $placeType->description }}</span>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No place types available</p>
                            @endforelse
                        </div>
                        @error('place_type_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Create Place of Interest
                        </button>
                        <a href="{{ route('places.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection