<!-- Muestra la informacion de un User en particular, en modo Card -->
<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        
        <h5 class="mb-2 text-xl font-medium leading-tight">{!! $trek->id !!} {{ $trek->name }}</h5>
        <p class="mb-4 text-sm text-neutral-500">{{ $trek->municipality->name }}</p>

        <!-- Botones de acciones -->
        <a href="{{route('trekCRUD.show' , ['trekCRUD' => $trek->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('trekCRUD.edit' , ['trekCRUD' => $trek->id ])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{route('trekCRUD.destroy' , ['trekCRUD' => $trek->id ])}}" method="POST" class="float-right">
            @method('DELETE')
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" >Delete</button>
        </form>
    </div>
</div>
