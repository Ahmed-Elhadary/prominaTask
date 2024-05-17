<?php
namespace App\Services;

use App\Contracts\AlbumServiceInterface;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlbumService implements AlbumServiceInterface
{
    public function getAllAlbums()
    {
        return Album::all();
    }

    public function createAlbum(Request $request)
    {
        $album= Album::create($request->all());
        $fileArray = explode(',', $request->file_ids);
        foreach ($fileArray as $file) {
            $album->addMedia(storage_path('app/temp/' . $file))->toMediaCollection('image');
        }
    }
    public function updateAlbum(Request $request, Album $album)
    {
        $album->update($request->all());
        $fileArray = explode(',', $request->file_ids);
        foreach ($fileArray as $file) {
            // Skip empty strings
            if (empty($file)) {
                continue;
            }
            $filePath = storage_path('app/temp/' . $file);
            // Check if the file exists before attempting to add it
            if (file_exists($filePath)) {
                $album->addMedia($filePath)->toMediaCollection('image');
            }
        }
    }

    public function deleteAlbum(Album $album)
    {
        $album->delete();
    }
}
