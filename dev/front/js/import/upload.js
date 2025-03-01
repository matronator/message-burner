const fileInput = document.querySelector(`.js-file`);
const filenameEl = document.querySelector(`.upload-filename`);
const filePreview = document.querySelector(`.file-preview`);

if (fileInput) {
    const removePreviewBtn = document.getElementById(`removeFile`);
    const thumbnail = document.querySelector(`.thumbnail`);

    filePreview.addEventListener(`click`, e => {
        if (e.target.id !== `removeFile`) {
            fileInput.click();
        }
    });

    const originalText = filenameEl.innerHTML;
    removePreviewBtn.addEventListener(`click`, () => {
        fileInput.value = ``;
        thumbnail.src = ``;
        filePreview.classList.add(`hidden`);
        filenameEl.innerHTML = originalText;
    });

    fileInput.addEventListener(`change`, e => {
        const files = fileInput.files;
        if (files[0]) {
            // Change filename
            /** @type String */
            const filename = e.target.value;
            const parts = filename.split(`\\`);
            filenameEl.innerHTML = parts[parts.length - 1];

            // Show preview
            filePreview.classList.remove(`hidden`);
            const reader = new FileReader();
            reader.onload = e => (thumbnail.src = e.target.result);
            reader.readAsDataURL(files[0]);
        }
    });
}
