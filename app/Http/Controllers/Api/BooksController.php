<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BooksResource;
use App\Http\Requests\BookRequest;
use App\Models\Books;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class BooksController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Books::query()
            ->where('title', 'like', "%{$search}%")
            ->orWhere('author', 'like', "%{$search}%")
            ->orWhere('isbn', 'like', "%{$search}%")
            ->orWhere('publisher', 'like', "%{$search}%")
            ->orWhere('published_date',$search)
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return BooksResource::collection($query);
    }

    public function store(BookRequest $request)
    {
        $data = $request->validated();
        // $data['created_by'] = $request->user()->id;
        // $data['updated_by'] = $request->user()->id;

        /** @var \Illuminate\Http\UploadedFile $image */
        $image = $data['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
        }

        $book = Books::create($data);

        return new BooksResource($book);
    }

    public function show($id)
    {
        $books = Books::find($id);
        return response()->json($books);
    }

    public function update(BookRequest $request, Books $book)
    {
        $data = $request->validated();
        // dd($data);
       /** @var \Illuminate\Http\UploadedFile $image */
       $image = $data['image'] != null ? $data['image'] : false;
       // Check if image was given and save on local file system
       if ($image) {
           $relativePath = $this->saveImage($image);
           $data['image'] = URL::to(Storage::url($relativePath));
           $data['image_mime'] = $image->getClientMimeType();
           $data['image_size'] = $image->getSize();

           // If there is an old image, delete it
           if ($book->image) {
               Storage::deleteDirectory('/public/' . dirname($book->image));
           }
       } else {
        $data['image'] = $book->image;
       }

       $book->update($data);

       return new BooksResource($book);
    }
    
    public function destroy(Books $book)
    {
        $book->delete();

        return response()->noContent();
    }

    private function saveImage(UploadedFile $image)
    {
        $path = 'images/' . Str::random();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }
}
