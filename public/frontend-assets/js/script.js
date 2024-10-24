document.addEventListener("DOMContentLoaded", function () {

    const uploadField = document.getElementById('documents');
    const filesContainer = document.getElementById('filesContainer');
    const uploadBtn = document.getElementById('submitButton');
    const uploadFilesForm = document.getElementById('uploadFilesForm');
    let removeIcons;
    let allfiles = [];

    uploadField.addEventListener("change", function (event) {

        const maxFileSize = parseInt(uploadField.getAttribute("max-filesize"));
        const maxFilesAllowed = parseInt(uploadField.getAttribute("max-files"));
        const newFiles = Array.from(event.target.files);

        if (allfiles.length + newFiles.length > maxFilesAllowed) {
            alert(`You can upload a maximum of ${maxFilesAllowed} files.`);
            return;
        }

        newFiles.forEach((file) => {

            if (!file.type.startsWith('image/')) {
                alert("only images are allowed");
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
            reader.onload = function (e) {
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

                    removeIcon.addEventListener("click", function () {

                        const dataId = Number(this.getAttribute('data-id'));
                        allfiles = allfiles.filter((file, index) => index !== dataId);
                        this.parentElement.remove();
                        reindexRemoveIcon();

                    });
                    removeIcon.setAttribute('data-listener', 'true');
                }

            });
        });

        allfiles = allfiles.concat(newFiles.filter(file => file.size / (1024 * 1024) <= maxFileSize));
    });

    function reindexRemoveIcon() {
        const updatedRemoveIcons = document.querySelectorAll(".remove");
        updatedRemoveIcons.forEach((updatedRemoveIcon, index) => {
            updatedRemoveIcon.setAttribute("data-id", index);
        });
    }

    uploadBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const dataTransfer = new DataTransfer();
        allfiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        uploadField.files = dataTransfer.files;

        uploadFilesForm.submit();
    })

});