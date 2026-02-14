<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\GuardarImagenRequest;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Meeting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CommentControllerCRUD extends Controller
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $meetings = Meeting::with('trek')->get();
        return view('comments.create', compact('meetings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request)
    {
        $validated = $request->validated();

        $comment = new Comment();
        $comment->comment = $validated['comment'];
        $comment->user_id = Auth::id();
        $comment->meeting_id = $validated['meeting_id'] ?? null;
        $comment->status = 'y'; // Active by default
        $comment->save();

        // Handle image upload if present
        if ($request->hasFile('url')) {
            $filename = time() . "." . $request->url->extension();
            $request->url->move(public_path('images'), $filename);
            Image::create([
                'url' => $filename,
                'comment_id' => $comment->id
            ]);
        }

        return redirect()->route('comments.index')
            ->with('status', 'Success: Comentario creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        // Load relationships
        $comment->load(['user', 'meeting.trek']);
        
        // Get the first image if exists
        $image = Image::where('comment_id', $comment->id)->first();
        $comment->image = $image;

        return view('comments.show', ['comment' => $comment]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Comment $comment)
    // {
    //     $comment->load(['user', 'meeting.trek']);
    //     $meetings = Meeting::with('trek')->get();
        
    //     // Get the first image if exists
    //     $image = Image::where('comment_id', $comment->id)->first();
    //     $comment->image = $image;

    //     return view('comments.edit', compact('comment', 'meetings'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $validated = $request->validated();

        $comment->comment = $validated['comment'];
        $comment->meeting_id = $validated['meeting_id'] ?? null;
        $comment->save();

        return redirect()->route('comments.index')
            ->with('status', 'Success: Comentario actualizado correctamente');
    }

    /**
     * Upload an image for a comment.
     */
    public function image(GuardarImagenRequest $request, Comment $comment)
    {


        $filename = time() . "." . $request->url->extension();
        $request->url->move(public_path('images'), $filename);

        Image::create([
            'url' => $filename,
            'comment_id' => $comment->id
        ]);

        return back()->with('status', 'Success: Imagen subida correctamente');
    }

    /**
     * Remove an image from storage.
     */
    public function destroyImage(Image $image)
    {
        // Delete from database
        $image->delete();

        // Delete from filesystem
        $imagePath = public_path('images/' . $image->url);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        return back()->with('status', 'Success: Imagen eliminada correctamente');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Comment $comment)
    {
        try {
            // Delete associated image if exists
            $image = Image::where('comment_id', $comment->id)->first();
            if ($image) {
                $this->destroyImage($image);
            }

            // Soft delete: set status to 'n'
            $comment->status = 'n';
            $comment->save();

            return redirect()->route('comments.index')
                ->with('status', 'Success: Comentario desactivado correctamente');
        } catch (Exception $e) {
            return redirect()->route('comments.index')
                ->with('status', 'Error: ' . $e->getMessage());
        }
    }
}