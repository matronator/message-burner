/**
 * Copyright (c) 2021 Matronator
 *
 * This software is released under the MIT License.
 * https://opensource.org/licenses/MIT
 */

const textarea = document.querySelector(`[data-text-editor="message"]`)
const wrapper = document.querySelector(`.editor-wrapper`)

const editor = document.createElement(`div`)
editor.id = `editor`
editor.className = `editor input placeholder`
editor.dataset.textInput = `message`
editor.innerText = textarea.getAttribute(`placeholder`)

wrapper.appendChild(editor)

textarea.addEventListener(`input`, e => {
    if (e.target.value !== ``) {
        editor.classList.remove(`placeholder`)
        editor.innerHtml = e.target.value
    } else {
        editor.classList.add(`placeholder`)
        editor.innerHtml = textarea.getAttribute(`placeholder`)
    }
})

textarea.addEventListener(`scroll`, () => {
    editor.scrollTop = textarea.scrollTop
})

function parseInput(input = ``) {

}
