import { createPopperLite as createPopper, preventOverflow, flip } from "@popperjs/core"

const popupTriggers = document.querySelectorAll(`[data-popup-target]`)

popupTriggers?.forEach(el => {
    const popup = document.querySelector(`[data-popup-id="${el.getAttribute('data-popup-target')}"]`)
    const popperInstance = createPopper(el, popup, {
        modifiers: [preventOverflow, flip],
        placement: `bottom`,
    })

    function show() {
        popup.classList.add(`show`)
        popup.setAttribute(`data-show`, ``)

        popperInstance.setOptions({
            modifiers: [{ name: `eventListeners`, enabled: true }],
        })

        popperInstance.update()
    }

    function hide() {
        popup.classList.remove(`show`)
        popup.removeAttribute(`data-show`)

        popperInstance.setOptions({
            modifiers: [{ name: `eventListeners`, enabled: false }],
        })

        popperInstance.update()
    }
    el.addEventListener(`click`, e => {
        show()
    })
    popup.addEventListener(`animationend`, () => {
        hide()
    })
})
