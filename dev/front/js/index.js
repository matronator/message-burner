import NetteForms from "../../../vendor/nette/forms/src/assets/netteForms.js"
import * as clipboard from "clipboard-polyfill/text";
import "./import/popup.js";

// nette forms
NetteForms.initOnLoad()

function registerAjaxHandlers() {
    const links = document.querySelectorAll(`a.ajax`)
    links?.forEach(link => {
        link.addEventListener(`click`, e => {
            e.preventDefault()
            handleNetteResponse(e.target.href)
        })
    })
}

async function handleNetteResponse(link, data, contentType = `application/json`) {
    const response = await fetch(link, {
        method: data ? `POST` : `GET`,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...(data && { 'Content-Type': contentType }),
        },
        ...(data && { body: data })
    })
    const { snippets = {}, redirect = `` } = await response.json()
    if (redirect !== ``) {
        window.location.replace(redirect)
    }
    Object.entries(snippets).forEach(([id, html]) => {
        const elem = document.getElementById(id)
        if (elem) elem.innerHTML = html
    })
    registerAjaxHandlers()
    // toggleHashGroups(location.href)
}

registerAjaxHandlers()

// document.getElementById("msg-link-text").addEventListener("click", copy, false);

document.addEventListener(`DOMContentLoaded`, () => {
    const messageLink = document.getElementById(`messageLink`)
    messageLink?.addEventListener(`click`, e => {
        clipboard.writeText(e.target.value)
    })
})
