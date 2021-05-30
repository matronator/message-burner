const fileInputs = document.querySelectorAll(`.js-file`)

if (fileInputs) {
  fileInputs.forEach(item => {
    const itemName = item.dataset.mainImage
    const clearButton = document.querySelector(
      `[data-clear-target="${itemName}"]`
    )
    if (clearButton) {
      clearButton.addEventListener(`click`, e => {
        document.querySelector(
          `[data-clear-image="${itemName}"]`
        ).value = `true`
        document
          .querySelector(`[data-image-preview="${itemName}"]`)
          .classList.add(`uk-hidden`)
        document.querySelector(`[data-image-desc="${itemName}"]`).value = ``
      })
    }
  })
}
