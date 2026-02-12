<!-- Muestra la informacion de un User en particular, en modo Card -->
<!-- Muestra la informacion de un User en particular, en modo Card -->
<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        
        <h5 class="mb-2 text-xl font-medium leading-tight">{!! $place->id !!} {{ $place->name }}</h5>
        {{-- <p class="mb-4 text-sm text-neutral-500">{{ $place->municipality->name }}</p> --}}

        <!-- Botones de acciones -->        

                <div class="flex gap-2">
            <a href="{{ route('places.show', ['place' => $place->id]) }}" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Show
            </a>
            <a href="{{ route('places.edit', ['place' => $place->id]) }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <form action="{{ route('places.destroy', ['place' => $place->id]) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este lugar?');">
                @method('DELETE')
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>