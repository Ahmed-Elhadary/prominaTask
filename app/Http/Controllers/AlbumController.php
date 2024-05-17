<?php

namespace App\Http\Controllers;

use App\Contracts\AlbumServiceInterface;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AlbumController extends Controller
{
    protected $albumService;
    public function __construct(AlbumServiceInterface $albumService)
    {
        $this->albumService = $albumService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $albums = $this->albumService->getAllAlbums();
        return view('album.index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('album.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->albumService->createAlbum($request);
        return redirect()->route('album.index')->with('success', 'Album created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        return view('album.show', compact('album'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Album $album)
    {
        $images = $album->getMedia('image');
        $existingFileIds = $images->pluck('id')->implode(','); // Get existing image IDs as a comma-separated string
        return view('album.edit', compact('album','images','existingFileIds'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        $this->albumService->updateAlbum($request, $album);
        return redirect()->route('album.index')->with('success', 'Album updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $album = Album::find($request->album_to_delete);
        if ($request->delete_option == 'delete') {
            $album->delete();
        } else {
            $mediaIds = $album->getMedia('image')->pluck('id');
            Media::whereIn('id', $mediaIds)->update(['model_id' => $request->move_album_id]);
            $album->delete();
        }
        return 'success';
    }
    public function image_destroy(Request $request)
    {
        Media::find($request->image_id)->delete();
        return redirect()->route('album.show',$request->album_id)->with('success', 'Album created successfully.');
    }

    public function uploadTemp(Request $request)
    {
        // Validate the file upload
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Generate a unique filename
        $filename = Str::uuid() . '_' . $file->getClientOriginalName();

        // Store the file in the 'temp' directory
        $path = $file->storeAs('temp', $filename);
        return response()->json([
            'id' => $filename,
            'url' => Storage::url($path),
        ]);
    }

    public function revertUpload(Request $request)
    {
        // Remove the temporary file
        $fileName = $request->getContent();
        Storage::delete('temp/' . $fileName);

        return response('', 204);
    }
}
