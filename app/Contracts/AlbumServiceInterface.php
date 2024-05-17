<?php
// app/Contracts/AlbumServiceInterface.php

namespace App\Contracts;

use App\Models\Album;
use Illuminate\Http\Request;

interface AlbumServiceInterface
{
    public function getAllAlbums();

    public function createAlbum(Request $request);

    public function updateAlbum(Request $request, Album $album);

    public function deleteAlbum(Album $album);
}
