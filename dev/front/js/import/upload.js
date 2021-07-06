document.addEventListener(`DOMContentLoaded`, () => {
    const fileInput = document.querySelector(`.js-file`)
    const filename = document.querySelector(`.upload-filename`)
    const fileList = document.querySelector(`.file-list`)

    if (fileInput) {
        fileInput.addEventListener(`change`, e => {
            filename.innerHTML = e.target.value
            const files = fileInput.files
            const reader = new FileReader()
            const thumbnail = document.querySelector(`.thumbnail`)
            thumbnail.height = 128
            reader.onload = e => (thumbnail.src = e.target.result)
            reader.readAsDataURL(files[0])
        })
    }

})
