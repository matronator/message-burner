document.addEventListener(`DOMContentLoaded`, () => {
    pageLoaded()

    baguetteBox.run(`.art-gallery`)

    const menuItems = document.querySelectorAll(`.menuitem`)
    menuItems.forEach(el => {
        el.addEventListener(`click`, evt => {
            if (evt.metaKey || evt.button === 1) return true
            if (evt.target !== el) {
                if (evt.target.hasAttribute(`data-location`)
                    || evt.target.hasAttribute(`href`)
                    || evt.target.hasAttribute(`src`)
                    || evt.target.hasAttribute(`onclick`)
                    || evt.target.hasAttribute(`data-gallery`)
                    || evt.target.hasAttribute(`data-image-href`)
                    || evt.target.tagName === `I`) return
            }
            evt.preventDefault()
            const contentDiv = el.querySelector(`.contentdiv`)
            menuItems.forEach(item => {
                if (el !== item) {
                    item.classList.remove(`toggled`)
                    item.querySelector(`.contentdiv`).classList.add(`hidden`)
                }
            })
            if (!el.classList.contains(`toggled`)) {
                history.pushState(null, null, el.getAttribute(`data-location`))
                el.classList.add(`toggled`)
                contentDiv.classList.remove(`hidden`)
            } else {
                history.pushState(null, null, `/`)
                el.classList.remove(`toggled`)
                contentDiv.classList.add(`hidden`)
            }
        })
    })

    const techCards = document.querySelectorAll(`.card-body`)
    techCards.forEach(el => {
        el.addEventListener(`click`, e => {
            e.stopImmediatePropagation()
            e.stopPropagation()
        })
    })

    const tabLinks = document.querySelectorAll(`.nav-link`)
    tabLinks.forEach(el => {
        el.addEventListener(`click`, evt => {
            if (evt.metaKey || evt.button === 1) return true
            evt.preventDefault()
            if (!el.classList.contains(`active`)) {
                history.pushState(null, null, el.getAttribute(`data-location`))
            }
        })
    })

    const imageLinks = document.querySelectorAll(`[data-image-href]`)
    if (imageLinks) {
        imageLinks.forEach(el => {
            el.addEventListener(`click`, e => {
                window.open(el.dataset.imageHref, `_blank`)
            })
        })
    }
})

window.addEventListener(`popstate`, evt => {
    pageLoaded()
})

function pageLoaded() {
    const parts = location.hash.indexOf(`-`) !== -1 ? location.hash.replace(`#`, ``)
        .split(`-`, 2) : [location.hash.replace(`#`, ``), ``]
    const page = parts[0]
    const hash = parts[1]
    console.log(`#${page}-${hash}`)
    document.querySelectorAll(`.contentdiv`).forEach(el => {
        el.classList.add(`hidden`)
    })
    document.querySelectorAll(`.menuitem`).forEach(el => {
        el.classList.remove(`toggled`)
    })
    if (page.length > 1) {
        const content = document.getElementById(page)
        if (content) {
            content.classList.add(`toggled`)
            if (content.querySelector(`.contentdiv`)) {
                content.querySelector(`.contentdiv`).classList.remove(`hidden`)
            }
        }
        if (hash.length > 1) {
            const activeLinks = document.querySelectorAll(`.nav-link.active`)
            if (activeLinks) {
                activeLinks.forEach(el => {
                    el.classList.remove(`active`)
                })
            }
            const activeTabs = document.querySelectorAll(`.tab-pane.active.show`)
            if (activeTabs) {
                activeTabs.forEach(el => {
                    el.classList.remove(`active`, `show`)
                })
            }
            const activeElements = document.querySelectorAll(`[data-active-hash="${hash}"]`)
            if (activeElements) {
                activeElements.forEach(el => {
                    el.classList.add(`active`)
                    if (el.classList.contains(`tab-pane`)) {
                        el.classList.add(`show`)
                    }
                })
            }
        }
    }
}
