<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit | Multiple images uploading</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="{{ asset('frontend-assets/css/style.css') }}">

</head>

<body>
    <div class="container">
        <form id="uploadFilesForm" action="{{ route('update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div
                class="position-relative input-container d-flex flex-column position-relative align-items-center justify-content-center mt-5">
                <p class="text-black fw-medium m-0">Upload your images or drag and drop it here</p>
                <p class="text-gray mt-2">Only images are allowed</p>
                <input class="position-absolute inset-0 w-100 h-100 opacity-0 cursor-pointer" type="file"
                    name="documents[]" id="documents" accept=".png, .jpg, jpeg, webp, .svg" max-filesize="5"
                    max-files="3" multiple>
                {{-- Files size is in MB --}}
            </div>

            <div class="d-flex flex-wrap gap-3 mt-3" id="filesContainer">
                @foreach ($files as $file)
                    <div class="file-item">
                        <span class="remove remove-exsisting" itemId="{{ $file->id }}">x</span>
                        <img class="image" src="{{ asset('storage/' . $file->documents) }}">
                    </div>
                @endforeach
            </div>
            <button id="submitButton" type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let existingFiles = @json($files);
            let removeExistingFiles = [];

            const uploadField = document.getElementById('documents');
            const filesContainer = document.getElementById('filesContainer');
            const uploadBtn = document.getElementById('submitButton');
            const uploadFilesForm = document.getElementById('uploadFilesForm');
            let removeIcons;
            let allfiles = [];

            const removeButtons = document.querySelectorAll(".remove-exsisting");
            removeButtons.forEach(removeButton => {
                removeButton.addEventListener("click", function() {
                    const itemId = removeButton.getAttribute("itemId");
                    removeExistingFiles.push(itemId);
                    existingFiles = existingFiles.filter(existingFile => existingFile.id != itemId);
                    removeButton.parentElement.remove();

                    const hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "remove_existing_files[]";
                    hiddenInput.value = itemId;
                    uploadFilesForm.append(hiddenInput);
                });
            });

            uploadField.addEventListener("change", function(event) {

                const maxFileSize = parseInt(uploadField.getAttribute("max-filesize"));
                const maxFilesAllowed = parseInt(uploadField.getAttribute("max-files"));
                const newFiles = Array.from(event.target.files);

                if (allfiles.length + newFiles.length + existingFiles.length > maxFilesAllowed) {
                    alert(`You can upload a maximum of ${maxFilesAllowed} files.`);
                    return;
                }

                newFiles.forEach((file) => {

                    if (!file.type.startsWith('image/')) {
                        alert("Only images are allowed");
                        return;
                    }

                    const fileSizeInMB = file.size / (1024 * 1024);
                    if (maxFileSize && fileSizeInMB > maxFileSize) {
                        alert(`File size should not exceed ${maxFileSize} MB`);
                        return;
                    }

                    const fileItem = document.createElement('div');
                    const image = document.createElement('img');
                    const removeBtn = document.createElement('span');

                    removeBtn.textContent = "x";

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        image.src = e.target.result;
                    }
                    reader.readAsDataURL(file);

                    fileItem.classList.add('file-item');
                    image.classList.add('image');
                    removeBtn.classList.add('remove');

                    fileItem.append(removeBtn);
                    fileItem.append(image);
                    filesContainer.append(fileItem);

                    removeIcons = document.querySelectorAll('.remove');
                    removeIcons.forEach((removeIcon, index) => {
                        removeIcon.setAttribute('data-id', index);

                        if (!removeIcon.hasAttribute('data-listener')) {

                            removeIcon.addEventListener("click", function() {

                                const dataId = Number(this.getAttribute('data-id'));
                                allfiles = allfiles.filter((file, index) =>
                                    index !== dataId);
                                this.parentElement.remove();
                                reindexRemoveIcon();

                            });
                            removeIcon.setAttribute('data-listener', 'true');
                        }

                    });
                });

                allfiles = allfiles.concat(newFiles.filter(file => file.size / (1024 * 1024) <=
                    maxFileSize));
            });

            function reindexRemoveIcon() {
                const updatedRemoveIcons = document.querySelectorAll(".remove");
                updatedRemoveIcons.forEach((updatedRemoveIcon, index) => {
                    updatedRemoveIcon.setAttribute("data-id", index);
                });
            }

            uploadBtn.addEventListener("click", function(e) {
                e.preventDefault();
                const dataTransfer = new DataTransfer();
                allfiles.forEach(file => {
                    dataTransfer.items.add(file);
                });

                uploadField.files = dataTransfer.files;
                uploadFilesForm.submit();
            })

        });
    </script>
</body>

</html>
