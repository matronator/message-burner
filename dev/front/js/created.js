import * as clipboard from "clipboard-polyfill/text";
import { InApp } from "./import/detect-inapp.js";

document.addEventListener(`DOMContentLoaded`, () => {
    const messageLink = document.getElementById(`messageLink`)
    if (messageLink) {
        const inApp = new InApp(navigator.userAgent || navigator.vendor || window.opera)

        if (inApp.isInApp || ['messenger', 'facebook'].includes(inApp.browser)) {
            messageLink.addEventListener(`click`, copyLinkInApp, false)
        } else {
            messageLink.addEventListener(`click`, copyLink, false)
        }
    }

    function copyLink() {
        messageLink.focus()
        messageLink.setSelectionRange(0, 9999)
        messageLink.select()
        clipboard.writeText(messageLink.value)
        // Because ^ it unselects the text for whatever reason
        messageLink.focus()
        messageLink.setSelectionRange(0, 9999)
        messageLink.select()
    }

    function copyLinkInApp() {
        messageLink.focus()
        messageLink.select()
        messageLink.setSelectionRange(0, 9999)
        document.execCommand("copy", false)
    }
})
