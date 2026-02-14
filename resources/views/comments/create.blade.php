@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Create Comment</h1>
            <a href="{{ route('comments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Comment * <span class="text-gray-500">(min 5 characters, max 500)</span>
                    </label>
                    <textarea 
                        id="comment" 
                        name="comment" 
                        rows="6"
                        minlength="5" 
                        maxlength="500" 
                        required
                        placeholder="Write your comment here..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('comment') border-red-500 @enderror"
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="meeting_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Meeting (optional)
                    </label>
                    <select 
                        id="meeting_id" 
                        name="meeting_id" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('meeting_id') border-red-500 @enderror"
                    >
                        <option value="">-- Select a meeting --</option>
                        @if(isset($meetings) && $meetings->count() > 0)
                            @foreach($meetings as $meeting)
                                <option value="{{ $meeting->id }}" {{ old('meeting_id') == $meeting->id ? 'selected' : '' }}>
                                    {{ $meeting->trek->name ?? 'No name' }} - {{ $meeting->date ?? 'No date' }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('meeting_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                        Image (optional)
                    </label>
                    <input 
                        type="file" 
                        name="url" 
                        id="url" 
                        accept="image/jpeg,image/png,image/jpg,image/gif" 
                        class="w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100
                            @error('url') border-red-500 @enderror" 
                        onchange="previewImage(event)" 
                    />
                    <p class="mt-1 text-xs text-gray-500">Allowed formats: JPG, PNG, GIF. Max 2MB.</p>
                    @error('url')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">Preview:</p>
                        <img id="preview" src="" alt="Preview" class="max-w-xs rounded shadow-lg border border-gray-200" />
                    </div>
                </div>
               
                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                        Create Comment
                    </button>
                    <a href="{{ route('comments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script for image preview -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const previewDiv = document.getElementById('imagePreview');
            const previewImg = document.getElementById('preview');
            
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