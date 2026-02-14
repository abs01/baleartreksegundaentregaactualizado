<!-- Muestra la información de un Municipality en particular, en modo Card -->
<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        
        <h5 class="mb-2 text-xl font-medium leading-tight">
            <span class="text-gray-500">#{{ $comment->id }}</span> {{ $comment->comment }}
        </h5>
        
        @if($comment->user)
            <p class="mb-4 text-sm text-neutral-600">
                <span class="font-semibold">Usuario:</span> {{ $comment->user->name }}
            </p>
        @endif

        @if($comment->meeting)
            <p class="mb-4 text-sm text-neutral-600">
                <span class="font-semibold">Excursión:</span> {{ $comment->meeting->trek->name ?? 'N/A' }}
            </p>
        @endif

        
        @if($comment->status)
            <p class="mb-4 text-sm text-neutral-600">
                <span class="font-semibold">Status:</span> {{ $comment->status ?? "'N/A'" }}
            </p>
        @endif
        
         @if ($comment->image)
            <p class="mb-4 text-sm">Imagen asociada al Comment: 
                <a href="{{ '/images/' . $comment->image->url }}" class="text-blue-600 hover:text-blue-800 underline">{{ $comment->image->url }}</a>
            </p>
        @endif
        
        <!-- Botones de acciones -->
        <div class="flex gap-2">
            <a href="{{ route('comments.show', $comment) }}" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Show
            </a>
            
            <form action="{{ route('comments.destroy', $comment) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar este comentario?');">
                @method('DELETE')
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Deactivate
                </button>
            </form>
        </div>
    </div>
</div>