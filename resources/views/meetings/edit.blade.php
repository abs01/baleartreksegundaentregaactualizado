@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-gray-100 px-6 py-4 border-b">
                <h2 class="text-2xl font-bold">Edit Meeting</h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Trek Selection -->
                    <div class="mb-4">
                        <label for="trek_id" class="block text-gray-700 text-sm font-bold mb-2">
                            Trek *
                        </label>
                        <select name="trek_id" id="trek_id" 
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('trek_id') border-red-500 @enderror" 
                                required>
                            <option value="">Selecciona un trek</option>
                            @foreach($treks as $trek)
                                <option value="{{ $trek->id }}" 
                                        {{ old('trek_id', $meeting->trek_id) == $trek->id ? 'selected' : '' }}>
                                    {{ $trek->name }} ({{ $trek->municipality->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('trek_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guide Selection -->
                    <div class="mb-4">
                        <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">
                            Guía Principal *
                        </label>
                        <select name="user_id" id="user_id" 
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('user_id') border-red-500 @enderror" 
                                required>
                            <option value="">Selecciona un guía</option>
                            @foreach($guides as $guide)
                                <option value="{{ $guide->id }}" 
                                        {{ old('user_id', $meeting->user_id) == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->name }} ({{ $guide->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Day -->
                    <div class="mb-4">
                        <label for="day" class="block text-gray-700 text-sm font-bold mb-2">
                            Día de la Quedada
                        </label>
                        <input type="date" 
                               name="day" 
                               id="day" 
                               value="{{ old('day', $meeting->day) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('day') border-red-500 @enderror">
                        @error('day')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time -->
                    <div class="mb-4">
                        <label for="time" class="block text-gray-700 text-sm font-bold mb-2">
                            Hora de Quedada
                        </label>
                        <input type="time" 
                               name="time" 
                               id="time" 
                               value="{{ old('time', $meeting->time) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('time') border-red-500 @enderror">
                        @error('time')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Application Date Start -->
                    <div class="mb-4">
                        <label for="appDateIni" class="block text-gray-700 text-sm font-bold mb-2">
                            Fecha de Inicio de Inscripciones *
                        </label>
                        <input type="date" 
                               name="appDateIni" 
                               id="appDateIni" 
                               value="{{ old('appDateIni', $meeting->appDateIni) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('appDateIni') border-red-500 @enderror" 
                               required>
                        @error('appDateIni')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Application Date End -->
                    <div class="mb-6">
                        <label for="appDateEnd" class="block text-gray-700 text-sm font-bold mb-2">
                            Fecha de Fin de Inscripciones *
                        </label>
                        <input type="date" 
                               name="appDateEnd" 
                               id="appDateEnd" 
                               value="{{ old('appDateEnd', $meeting->appDateEnd) }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('appDateEnd') border-red-500 @enderror" 
                               required>
                        @error('appDateEnd')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Actualizar Quedada
                        </button>
                        <a href="{{ route('meetings.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validate that appDateEnd is after appDateIni
    document.getElementById('appDateIni').addEventListener('change', function() {
        document.getElementById('appDateEnd').min = this.value;
    });
    
    // Set initial min value for appDateEnd
    if (document.getElementById('appDateIni').value) {
        document.getElementById('appDateEnd').min = document.getElementById('appDateIni').value;
    }
</script>
@endpush
@endsection