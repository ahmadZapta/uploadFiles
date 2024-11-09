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
                        <span class="remove remove-exsisting" itemId="{{ $file->id }}" data-listener="false">x</span>
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

            const UploadFilesObject = {

                uploadField: document.getElementById('documents'),
                existingFiles: @json($files),
                allfiles: [],

                init() {
                    this.handleUploadFilesChange();
                    this.handleRemovingFiles();
                    this.handleFormSubmit();
                },

                handleUploadFilesChange() {
                    
                    function reindexRemoveIcon() {
                        const updatedRemoveIcons = document.querySelectorAll(".remove");
                        updatedRemoveIcons.forEach((updatedRemoveIcon, index) => {
                            updatedRemoveIcon.setAttribute("data-id", index);
                        });
                    }

                    this.uploadField?.addEventListener("change", function(event) {
                        const maxFileSize = parseInt(UploadFilesObject.uploadField.getAttribute(
                            "max-filesize"));
                        const maxFilesAllowed = parseInt(UploadFilesObject.uploadField.getAttribute(
                            "max-files"));
                        const newFiles = Array.from(event.target.files);

                        if (UploadFilesObject.allfiles.length + newFiles.length + UploadFilesObject.existingFiles.length >
                            maxFilesAllowed) {
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

                                        const dataId = Number(this.getAttribute(
                                            'data-id'));
                                        UploadFilesObject.allfiles =
                                            UploadFilesObject.allfiles.filter((
                                                    file,
                                                    index) =>
                                                index !== dataId);
                                        this.parentElement.remove();
                                        reindexRemoveIcon();

                                    });
                                    removeIcon.setAttribute('data-listener', 'true');
                                }

                            });
                        });

                        UploadFilesObject.allfiles = UploadFilesObject.allfiles.concat(newFiles.filter(
                            file => file.size / (1024 * 1024) <=
                            maxFileSize));
                    });
                },

                handleRemovingFiles() {

                    const removeButtons = document.querySelectorAll(".remove-exsisting");
                    let removeExistingFiles = []
                    removeButtons?.forEach(removeButton => {
                        removeButton.addEventListener("click", function() {
                            const itemId = removeButton.getAttribute("itemId");
                            removeExistingFiles.push(itemId);
                            UploadFilesObject.existingFiles = UploadFilesObject.existingFiles.filter(existingFile => existingFile
                                .id != itemId);
                            removeButton.parentElement.remove();

                            const hiddenInput = document.createElement("input");
                            hiddenInput.type = "hidden";
                            hiddenInput.name = "remove_existing_files[]";
                            hiddenInput.value = itemId;
                            uploadFilesForm.append(hiddenInput);
                        });
                    });
                },

                handleFormSubmit() {
                    const uploadBtn = document.getElementById('submitButton');
                    uploadBtn?.addEventListener("click", function(e) {
                        const uploadFilesForm = document.getElementById('uploadFilesForm');
                        e.preventDefault();
                        const dataTransfer = new DataTransfer();
                        UploadFilesObject.allfiles.forEach(file => {
                            dataTransfer.items.add(file);
                        });

                        UploadFilesObject.uploadField.files = dataTransfer.files;
                        uploadFilesForm.submit();
                    })
                },
            }

            UploadFilesObject.init();

        });
    </script>
</body>
</html>
