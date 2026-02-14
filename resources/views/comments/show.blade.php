@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Comment Details</h1>
            <a href="{{ route('comments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        @if ($message = Session::get('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ $message }}
            </div>
        @endif

        <!-- Comment Card -->
        <div class="mb-6">
            <x-card-comment :comment="$comment" />
        </div>

        <!-- Image Section -->
        @if($comment->image)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Associated Image</h2>
                <img 
                    src="{{ asset('images/' . $comment->image->url) }}" 
                    alt="Comment image" 
                    class="max-w-full h-auto rounded shadow-lg border border-gray-200"
                >
                
                <!-- Delete image button -->
                <form action="{{ route('comments.image.destroy', $comment->image) }}" 
                      method="POST" 
                      class="mt-4"
                      onsubmit="return confirm('Are you sure you want to delete this image?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Image
                    </button>
                </form>
            </div>
        @else
            <!-- Upload image form if no image exists -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Add Image</h2>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('comments.image', $comment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                            Select an image
                        </label>
                        <input 
                            type="file" 
                            name="url" 
                            id="url" 
                            accept="image/*" 
                            required
                            class="w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100"
                            onchange="previewUploadImage(event)"
                        />
                        @error('url')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="uploadPreview" class="mb-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">Preview:</p>
                        <img id="uploadPreviewImg" src="" alt="Preview" class="max-w-xs rounded shadow-lg border border-gray-200" />
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Upload Image
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Script for image preview -->
    <script>
        function previewUploadImage(event) {
            const file = event.target.files[0];
            const previewDiv = document.getElementById('uploadPreview');
            const previewImg = document.getElementById('uploadPreviewImg');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewDiv.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewDiv.classList.add('hidden');
            }
        }
    </script>
@endsection