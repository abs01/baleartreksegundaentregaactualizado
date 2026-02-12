@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-gray-100 px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Create Trek</h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('trekCRUD.store') }}" method="POST">
                    @csrf
                    
                    <!-- Trek Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                            Trek Name *
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

                    <!-- Municipality -->
                    <div class="mb-4">
                        <label for="municipality_id" class="block text-gray-700 text-sm font-bold mb-2">
                            Municipality *
                        </label>
                        <select name="municipality_id" 
                                id="municipality_id" 
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('municipality_id') border-red-500 @enderror" 
                                required>
                            <option value="">Select a municipality</option>
                            @foreach($municipalities as $municipality)
                                <option value="{{ $municipality->id }}" {{ old('municipality_id') == $municipality->id ? 'selected' : '' }}>
                                    {{ $municipality->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('municipality_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Interesting Places -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Interesting Places
                        </label>
                        <div class="border rounded p-4 bg-gray-50 max-h-60 overflow-y-auto">
                            @forelse($interestingPlaces as $place)
                                <div class="flex items-start mb-2">
                                    <input type="checkbox" 
                                           name="interesting_places[]" 
                                           value="{{ $place->id }}" 
                                           id="place_{{ $place->id }}"
                                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ in_array($place->id, old('interesting_places', [])) ? 'checked' : '' }}>
                                    <label for="place_{{ $place->id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                        {{ $place->name }} 
                                        <span class="text-gray-500">({{ $place->municipality->name ?? 'N/A' }})</span>
                                    </label>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No interesting places available</p>
                            @endforelse
                        </div>
                        @error('interesting_places')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Create Trek
                        </button>
                        <a href="{{ route('trekCRUD.index') }}" 
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
