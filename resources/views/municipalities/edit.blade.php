@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Edit Municipality</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('municipalities.update', $municipality) }}" method="POST" 
          class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-2xl">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Name *
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 
                   @error('name') border-red-500 @enderror"
                   id="name" type="text" name="name"
                   value="{{ old('name', $municipality->name) }}" required>
            @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>


        <!-- Island -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="island_id">
                Island *
            </label>
            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 
                    @error('island_id') border-red-500 @enderror"
                    id="island_id" name="island_id" required>
                <option value="">Select island</option>
                @foreach($islands as $island)
                    <option value="{{ $island->id }}"
                        {{ old('island_id', $municipality->island_id) == $island->id ? 'selected' : '' }}>
                        {{ $island->name }}
                    </option>
                @endforeach
            </select>
            @error('island_id')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Zone -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="zone_id">
                Zone *
            </label>
            <select class="shadow border rounded w-full py-2 px-3 text-gray-700 
                    @error('zone_id') border-red-500 @enderror"
                    id="zone_id" name="zone_id" required>
                <option value="">Select zone</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}"
                        {{ old('zone_id', $municipality->zone_id) == $zone->id ? 'selected' : '' }}>
                        {{ $zone->name }}
                    </option>
                @endforeach
            </select>
            @error('zone_id')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Update Municipality
            </button>
            <a href="{{ route('municipalities.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
