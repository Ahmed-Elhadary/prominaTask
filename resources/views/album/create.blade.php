<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Album</div>

                    <div class="card-body">
                        <form action="{{ route('album.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Album Name</label>
                                <input type="text" class="form-control" id="name" name="name"  required>
                            </div>
                            <div class="form-group">
                                <input type="file" name="file" id="filepond" multiple>
                                <input type="hidden" name="file_ids" id="file_ids">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Add Album</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Register the FilePond plugin
            FilePond.registerPlugin(FilePondPluginFileRename);

            // Turn input element into a pond
            const inputElement = document.querySelector('input[id="filepond"]');
            const fileIdsInput = document.querySelector('input[id="file_ids"]');
            let fileIds = [];

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
                            fileIds.push(res.id);
                            fileIdsInput.value = fileIds.join(',');
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
                        },
                        onload: (response) => {
                            fileIds = fileIds.filter(id => id !== response);
                            fileIdsInput.value = fileIds.join(',');
                        }
                    }
                },
                allowMultiple: true,
                allowFileRename: true,
                fileRenameFunction: (file) => {
                    const extension = file.name.split('.').pop();
                    const newName =
                        `${Date.now()}-${Math.random().toString(36).substring(7)}.${extension}`;
                    console.log('Renaming file to:', newName); // Debug log to see the renamed file name
                    return newName;
                },
            });

            pond.setOptions({
                fileRenameFunction: (file) =>
                    new Promise((resolve) => {
                        resolve(window.prompt('Enter new filename', file.name));
                    }),
            });

            pond.on('processfile', (error, file) => {
                if (error) {
                    console.error('Error processing file:', error);
                } else {
                    console.log('File processed successfully:', file);
                }
            });

        });
    </script>
</body>

</html>
