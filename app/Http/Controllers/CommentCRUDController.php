<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarImagenRequest;
use App\Models\Comment;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CommentCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::orderBy('id')->paginate(3);
        $user = Comment::with('user')->get();
        $meeting = Comment::with('meeting')->get();
        return view('comments.index', compact('comments', 'user', 'meeting')); // $comments = Comment::orderBy('id')->paginate(3); // return view('comments.index', compact('comments'));// $comments = Comment::orderBy('id')->paginate(3); // return
    }
public function image(GuardarImagenRequest $request, Comment $comment) {

    // Recordar que la ruta definida es
    // '/commentCRUD/{comment}/edit/image'

        $filename = time().".".$request->url->extension();
        $request->url->move(public_path('images'), $filename);
        
        Image::create(['url' => $filename, 'comment_id' => $comment->id]);

        // return a la misma pantalla para que muestre la imágen recién cargada
        return back(); 
    }
    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('comments.create');
    // }


    
public function destroyImage(Image $image)
    {
        // Eliminación del recurso

        // Borrar el registro de la DDBB
        $image->delete();

        // Borrar la imagen del directorio public/images
        $imagePath = public_path('images/' . $image->url);
        $windowsPath = str_replace('/', DIRECTORY_SEPARATOR, $imagePath);
    
        File::delete($windowsPath); 

        return back(); // return a la misma pantalla para que recargue sin la imágen eliminada
    }
    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate(['content' => 'required|string|max:255', 'user_id' => 'required|exists:users,id', 'meeting_id' => 'required|exists:meetings,id',]);
    //     Comment::create($validated);
    //     return redirect()->route('comments.index')->with('success', 'Comment created successfully');
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comment::with('user', 'meeting')->findOrFail($id);
        return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     $comment = Comment::with('user', 'meeting')->findOrFail($id);
    //     return view('comments.edit', compact('comment'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $validated = $request->validate(['content' => 'required|string|max:255', 'user_id' => 'required|exists:users,id', 'meeting_id' => 'required|exists:meetings,id',]);
    //     $comment = Comment::findOrFail($id);
    //     $comment->update($validated);
    //     return redirect()->route('comments.index')->with('success', 'Comment updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
        $comment = Comment::findOrFail($id);
        // Soft delete: set status to 'n' instead of deleting the record
        $comment->status = 'n';
        $this->destroyImage($comment->image);  
        $comment->save();
            return redirect()->route('comments.index')->with('success', 'Comment deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('comments.index')->with('error', 'Error al eliminar el comentario: ' . $e->getMessage());
        }
    }
}
