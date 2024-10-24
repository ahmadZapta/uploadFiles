<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multiple images uploading</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}">

</head>

<body>
    <div class="container">
        <form id="uploadFilesForm" action="{{ route('creatFiles') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="position-relative input-container d-flex flex-column position-relative align-items-center justify-content-center mt-5">
                <p class="text-black fw-medium m-0">Upload your images or drag and drop it here</p>
                <p class="text-gray mt-2">Only images are allowed</p>
                <input class="position-absolute inset-0 w-100 h-100 opacity-0 cursor-pointer" type="file" name="documents[]" id="documents"
                    accept=".png, .jpg, jpeg, webp, .svg" max-filesize="5" max-files="3" multiple>
                    {{-- Files size is in MB --}}
            </div>

            <div class="d-flex flex-wrap gap-3 mt-3" id="filesContainer"></div>
            <button id="submitButton" type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="{{ asset('frontend-assets/js/script.js') }}"></script>
</body>

</html>
