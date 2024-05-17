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
            width: 150px;
            height: 100px;
            object-fit: contain;
            border: 1px solid #DDD;
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
            width: 170px;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="card album-veiw">
        <div class="card-header" style="display: flex; place-content: space-between;">
            <div class="title">
              <a href="{{route('album.index')}}">ALBUMS</a>
            </div>
            <div class="button">
                <a class="btn btn-primary" href="{{ route('album.create') }}">Create Album</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row">
                    @foreach ($album->image as $image)
                        <div class="col-md-3 mt-3 text-center">
                            <div class="item ">
                                <form action="{{ route('image.destroy') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="album_id" value="{{ $album->id }}" >
                                    <input type="hidden" name="image_id" value="{{ $image->id }}" >
                                    <button class="btn btn-danger btn-sm" type="submit" style="height: 24px;
                                    margin: 10px;
                                    line-height: 13px;">X</button>
                                </form>
                                <a href="{{ $image->getUrl() }}" target="_blank">
                                    <img src="{{ $image->getUrl() }}" alt="SEO Keywords">
                                </a>
                            </div>
                            <p class="mt-2">
                                @php
                                    $name_split = explode('_', $image->name);
                                    $name = $name_split[1];
                                @endphp
                                {{ $name }}
                            </p>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>




    </div>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
