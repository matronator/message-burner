export function loadAds() {
    window.addEventListener(`load`, () => {
        const placeholders = document.querySelectorAll(`[data-banner-ad]`);
        placeholders.forEach(placeholder => {
            const attributes = placeholder.getAttribute(`data-attributes`).split(",");
            const elementType = placeholder.getAttribute(`data-element`);
            const element = document.createElement(elementType);
            attributes.forEach(attr => {
                element.setAttribute(attr, placeholder.dataset[attr]);
            });
            placeholder.replaceWith(element);
        });
    });
}
