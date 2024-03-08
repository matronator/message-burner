/**
 * Copyright (c) 2021 Matronator
 *
 * This software is released under the MIT License.
 * https://opensource.org/licenses/MIT
 */


const darkmodeBtn = document.querySelector(`[data-current-theme]`)
if (localStorage.getItem(`theme`) === `dark`) {
    darkmodeBtn.setAttribute(`data-current-theme`, `dark`)
    darkmodeBtn.setAttribute(`title`, `Use system settings`)
    darkmodeBtn.setAttribute(`aria-label`, `Use system settings`)
    darkmodeBtn.innerHTML = `🌗`
} else if (localStorage.getItem(`theme`) === `light`) {
    darkmodeBtn.setAttribute(`data-current-theme`, `light`)
    darkmodeBtn.setAttribute(`title`, `Dark mode`)
    darkmodeBtn.setAttribute(`aria-label`, `Dark mode`)
    darkmodeBtn.innerHTML = `🌑`
} else {
    darkmodeBtn.setAttribute(`data-current-theme`, `system`)
    darkmodeBtn.setAttribute(`title`, `Light mode`)
    darkmodeBtn.setAttribute(`aria-label`, `Light mode`)
    darkmodeBtn.innerHTML = `🌕`
}
darkmodeBtn.addEventListener(`click`, e => {
    setTheme()
})
darkmodeBtn.addEventListener(`keydown`, e => {
    if (e.code === `Enter` || e.code === `Space`) {
        e.preventDefault()
        setTheme()
    }
})

function setTheme() {
    const darkmodeBtn = document.querySelector(`[data-current-theme]`)
    if (darkmodeBtn.getAttribute(`data-current-theme`) === `system`) {
        chooseTheme(`light`)
    } else if (darkmodeBtn.getAttribute(`data-current-theme`) === `light`) {
        chooseTheme(`dark`)
    } else {
        chooseTheme()
    }
}

function chooseTheme(theme = `system`) {
    const darkmodeBtn = document.querySelector(`[data-current-theme]`)
    const root = document.documentElement;
    if (theme === `dark`) {
        localStorage.setItem(`theme`, `dark`)
        root.classList.add(`dark`)
        darkmodeBtn.setAttribute(`data-current-theme`, `dark`)
        darkmodeBtn.setAttribute(`title`, `Use system settings`)
        darkmodeBtn.setAttribute(`aria-label`, `Use system settings`)
        darkmodeBtn.innerHTML = `🌗`
    } else if (theme === `light`) {
        localStorage.setItem(`theme`, `light`)
        root.classList.remove(`dark`)
        darkmodeBtn.setAttribute(`data-current-theme`, `light`)
        darkmodeBtn.setAttribute(`title`, `Dark mode`)
        darkmodeBtn.setAttribute(`aria-label`, `Dark mode`)
        darkmodeBtn.innerHTML = `🌑`
    } else {
        localStorage.removeItem(`theme`)
        darkmodeBtn.setAttribute(`data-current-theme`, `system`)
        darkmodeBtn.setAttribute(`title`, `Light mode`)
        darkmodeBtn.setAttribute(`aria-label`, `Light mode`)
        darkmodeBtn.innerHTML = `🌕`
        if (window.matchMedia(`(prefers-color-scheme: dark)`).matches) {
            root.classList.add(`dark`)
        } else {
            root.classList.remove(`dark`)
        }
    }
}
