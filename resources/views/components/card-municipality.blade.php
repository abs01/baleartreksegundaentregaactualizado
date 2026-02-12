<!-- Muestra la información de un Municipality en particular, en modo Card -->
<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        
        <h5 class="mb-2 text-xl font-medium leading-tight">
            <span class="text-gray-500">#{{ $municipality->id }}</span> {{ $municipality->name }}
        </h5>
        
        @if($municipality->island)
            <p class="mb-4 text-sm text-neutral-600">
                <span class="font-semibold">Isla:</span> {{ $municipality->island->name }}
            </p>
        @endif

        <!-- Botones de acciones -->
        <div class="flex gap-2">
            <a href="{{ route('municipalities.show', ['municipality' => $municipality->id]) }}" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Show
            </a>
            <a href="{{ route('municipalities.edit', ['municipality' => $municipality->id]) }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <form action="{{ route('municipalities.destroy', ['municipality' => $municipality->id]) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este municipio?');">
                @method('DELETE')
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>