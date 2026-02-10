<!-- Muestra la informacion de un User en particular, en modo Card -->
<div class="block rounded-lg bg-white shadow-secondary-1">
    <div class="p-6 text-surface">
        
        <h5 class="mb-2 text-xl font-medium leading-tight">{!! $user->id !!} {{ $user->name }}</h5>

        <p class="mb-4 text-base">{!! $user->email !!}</p>
        <p class="mb-4 text-sm">role: {{$user->role->name ?? 'N/A'}}</p>
        <p class="mb-4 text-sm">created at: {{ $user->created_at }}</p>
        <p class="mb-4 text-sm">updated at: {{ $user->updated_at }}</p>

        <!-- Botones de acciones -->
        <a href="{{route('userCRUD.show' , ['userCRUD' => $user->id])}}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Show</a>
        <a href="{{route('userCRUD.edit' , ['userCRUD' => $user->id ])}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <form action="{{route('userCRUD.destroy' , ['userCRUD' => $user->id ])}}" method="POST" class="float-right">
            @method('DELETE')
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" >Delete</button>
        </form>
    </div>
</div>
