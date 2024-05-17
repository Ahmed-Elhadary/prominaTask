<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Album</title>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet" />
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Album</div>

                    <div class="card-body">
                        <form action="{{ route('album.update', $album->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Album Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $album->name }}" required>
                            </div>
                            <div class="form-group">
                                <input type="file" name="file" id="filepond" multiple>
                                <input type="hidden" name="file_ids" id="file_ids" value="{{ $existingFileIds }}">
                                <input type="hidden" name="new_file_ids" id="new_file_ids" value="">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Album</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Register the FilePond plugins
            FilePond.registerPlugin(FilePondPluginImageEdit);

            // Turn input element into a pond
            const inputElement = document.querySelector('input[id="filepond"]');
            const fileIdsInput = document.querySelector('input[id="file_ids"]');
            const existingFiles = {!! json_encode($images->map(function($image) { return ['id' => $image->id, 'source' => $image->getUrl(), 'options' => ['type' => 'local']]; })) !!};

            const pond = FilePond.create(inputElement, {
                server: {
                    process: {
                        url: '{{ route('upload.temp') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        onload: (response) => {
                            const res = JSON.parse(response);
                            // Append the new file ID to the existing file IDs
                            const existingFileIdsInput = document.querySelector('input[id="file_ids"]');
                            const newFileIdsInput = document.querySelector('input[id="new_file_ids"]');
                            const existingFileIds = existingFileIdsInput.value.split(',');
                            existingFileIds.push(res.id);
                            existingFileIdsInput.value = existingFileIds.join(',');

                            // Update the value of new file IDs
                            const newFileIds = newFileIdsInput.value ? newFileIdsInput.value.split(',') : [];
                            newFileIds.push(res.id);
                            newFileIdsInput.value = newFileIds.join(',');

                            return res.id;
                        },
                        onerror: (response) => {
                            console.error(response);
                        }
                    },
                    revert: {
                        url: '{{ route('revert.upload') }}',
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                },
                allowMultiple: true,
                allowFileRename: true,
                allowImageEdit: true, // Enable image editing
                fileRenameFunction: (file) => {
                    const extension = file.name.split('.').pop();
                    const newName = `${Date.now()}-${Math.random().toString(36).substring(7)}.${extension}`;
                    return newName;
                },
                files: existingFiles.map(file => ({
                    source: file.source,
                    options: {
                        type: 'local'
                    }
                }))
            });
        });
    </script>

</body>

</html>
