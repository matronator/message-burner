import NetteForms from "../../../vendor/nette/forms/src/assets/netteForms.js"
import * as clipboard from "clipboard-polyfill/text";
import axette from "axette";
import "./import/darkmode.js";
import "./import/popup.js";

// nette forms
NetteForms.initOnLoad()

axette.init()

// document.getElementById("msg-link-text").addEventListener("click", copy, false);

document.addEventListener(`DOMContentLoaded`, () => {
    const messageLink = document.getElementById(`messageLink`)
    if (messageLink) {
        messageLink.addEventListener(`click`, copyLink, false)
    }
})

function copyLink() {
    const messageLink = document.getElementById(`messageLink`)
    clipboard.writeText(messageLink.value)
    messageLink.focus()
    messageLink.setSelectionRange(0, 9999)
    messageLink.select()
}
