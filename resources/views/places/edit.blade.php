@extends('layouts.app')
@section('content')
    <div class="container mx-auto py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-100 px-6 py-4 border-b">
                    <h2 class="text-2xl font-bold">Edit Place of Interest</h2>
                </div>

                <div class="p-6">
                    <form action="{{ route('places.update', $place) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Place Name -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Place of Interest Name *
                            </label>
                            <input type="text" name="name" value="{{ old('name', $place->name) }}"
                                class="shadow border rounded w-full py-2 px-3" required>
                        </div>
                        <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Place Types *
                        </label>
                        <div class="border rounded p-4 bg-gray-50 max-h-60 overflow-y-auto">
                            @foreach ($placeTypes as $placeType)
                                <div class="flex items-start mb-2">
                                    <input type="radio" name="place_type_id" value="{{ $placeType->id }}"
                                        {{ old('place_type_id', $place->place_type_id) == $placeType->id ? 'checked' : '' }}
                                        required>
                                    <label class="ml-2 text-sm text-gray-700 cursor-pointer">
                                        <span class="font-medium">{{ $placeType->name }}</span>
                                        @if($placeType->description)
                                            <span class="block text-gray-500 text-xs mt-1">{{ $placeType->description }}</span>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
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
