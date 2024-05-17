<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .album-veiw img {
            width: 100px;
            height: 100px;
            object-fit: contain;

        }

        .album-veiw a {
            text-decoration: none;

        }

        .album-veiw a p {
            color: #333;
            font-weight: 600;
        }

        .album-veiw .btn-group {
            position: absolute;
            top: -3px;
            right: -14px;
            font-size: 25px;
            cursor: pointer;
        }

        .album-veiw .item {
            position: relative;
            width: 120px;
            margin: auto;
        }

    </style>
</head>

<body>
    <div class="card album-veiw">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="card-header" style="display: flex; place-content: space-between;">
            <div class="title">
                ALBUMS
            </div>
            <div class="button">
                <a class="btn btn-primary" href="{{ route('album.create') }}">Create Album</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row">
                    @foreach ($albums as $album)
                    <div class="col-md-3 mt-4">
                        <div class="item text-center">
                            <div class="btn-group">
                                <a class="dropdown-toggle toggle-menu-list" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="folder-actions fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu">

                                    <a class="dropdown-item" href="{{ route('album.edit', $album->id) }}">
                                        Edit </a>
                                    <hr style="margin: 2px 0 0;">
                                    <a class="dropdown-item delete-album" href="#" type="button" data-toggle="modal" data-id="{{ $album->id }}" data-target="#exampleModal">
                                        delete </a>



                                </div>
                            </div>
                            <a href="{{ route('album.show', $album->id) }}">
                                <img src="{{ asset('asset/images/3460517.png') }}" alt="SEO Keywords">
                                <p>
                                    {{ $album->name }}
                                </p>
                            </a>

                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Album </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="album_id" value="" class="album_id">

                        <input type="radio" id="delete" name="check_delete" value="delete">
                        <label for="delete">Delete With internal images</label><br>
                        <input type="radio" id="move" name="check_delete" value="move">
                        <label for="move">move Images To Another Albums</label><br>

                        <select class="form-control d-none another-album" name="album" id="">
                            @foreach ($albums as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary delete-album-submit">Delete</button>
                    </div>
                </div>
            </div>
        </div>

    </div>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function() {

            $('input[type="radio"]').change(function() {
                if ($(this).val() == "move") {
                    $('.another-album').removeClass('d-none');
                } else {
                    $('.another-album').addClass('d-none');
                }
            });

            $(document).on('click', '.delete-album', function() {
                var album_id = $(this).data('id');
                $('.album_id').val(album_id);
            });


            $('.delete-album-submit').on('click', function(e) {
                e.preventDefault();
                var albumId = $(this).data('id');
                var deleteOption = $('input[name="check_delete"]:checked').val();
                var moveAlbumId = $('.another-album').val();
                var albumToDelete = $('.album_id').val();

                $.ajax({
                    url: '/album/' + albumId
                    , type: 'DELETE'
                    , data: {
                        "_token": "{{ csrf_token() }}"
                        , "delete_option": deleteOption
                        , "move_album_id": moveAlbumId
                        , "album_to_delete": albumToDelete
                    }
                    , success: function(response) {
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        alert('Error deleting album');

                    }
                });

            });
        });

    </script>
</body>

</html>
