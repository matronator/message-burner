import * as clipboard from "clipboard-polyfill/text";
import axette from "axette";
import "./import/darkmode.js";
import "./import/popup.js";
import "./../../../app/modules/Front/components/MessageForm/MessageForm.js"

axette.init()
axette.fixURL()

const messageLink = document.getElementById(`messageLink`)
if (messageLink) {
    messageLink.addEventListener(`click`, copyLink, false)
}

function copyLink() {
    clipboard.writeText(messageLink.value)
    messageLink.focus()
    messageLink.setSelectionRange(0, 9999)
    messageLink.select()
}
