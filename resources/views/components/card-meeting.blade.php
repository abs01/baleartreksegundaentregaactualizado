<!-- Muestra la información de un Meeting en particular, en modo Card -->
<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">

        <h5 class="mb-2 text-xl font-medium leading-tight">
            <span class="text-gray-500">#{{ $meeting->id }}</span> {{ $meeting->trek->name ?? 'N/A' }}
        </h5>

        <div class="mb-4 space-y-1">
            <p class="text-sm text-neutral-600">
                <span class="font-semibold">Fecha inicio:</span>
                {{ $meeting->appDateIni ? \Carbon\Carbon::parse($meeting->appDateIni)->format('d/m/Y') : 'N/A' }}
            </p>
            <p class="text-sm text-neutral-600">
                <span class="font-semibold">Fecha fin:</span>
                {{ $meeting->appDateEnd ? \Carbon\Carbon::parse($meeting->appDateEnd)->format('d/m/Y') : 'N/A' }}
            </p>


            @if ($meeting->trek && $meeting->trek->municipality)
                <p class="text-sm text-neutral-600">
                    <span class="font-semibold">Municipio:</span> {{ $meeting->trek->municipality->name }}
                </p>
            @endif

            @if ($meeting->trek && $meeting->trek->municipality && $meeting->trek->municipality->zone)
                <p class="text-sm text-neutral-600">
                    <span class="font-semibold">Zona:</span> {{ $meeting->trek->municipality->zone->name }}
                </p>
            @endif

            @if ($meeting->trek && $meeting->trek->municipality && $meeting->trek->municipality->island)
                <p class="text-sm text-neutral-600">
                    <span class="font-semibold">Isla:</span> {{ $meeting->trek->municipality->island->name }}
                </p>
            @endif

        </div>

        <!-- Botones de acciones -->
        <div class="flex gap-2">
            <a href="{{ route('meetings.show', ['meeting' => $meeting->id]) }}" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Show
            </a>
            <a href="{{ route('meetings.edit', ['meeting' => $meeting->id]) }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <form action="{{ route('meetings.destroy', ['meeting' => $meeting->id]) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta quedada?');">
                @method('DELETE')
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
