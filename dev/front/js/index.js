import NetteForms from "../../../vendor/nette/forms/src/assets/netteForms.js"

// nette forms
NetteForms.initOnLoad()

async function handleNetteResponse(link, data, contentType = `application/json`) {
    const response = await fetch(link, {
        method: data ? `POST` : `GET`,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...(data && { 'Content-Type': contentType }),
        },
        signal: ajaxSignal,
        ...(data && { body: data })
    })
    const { snippets = {}, redirect = `` } = await response.json()
    if (redirect !== ``) {
        window.location.replace(redirect)
    }
    Object.entries(snippets).forEach(([id, html]) => {
        const elem = document.getElementById(id)
        elem?.innerHTML = html
    })
    // registerAjaxHandlers(link)
    // toggleHashGroups(location.href)
}
