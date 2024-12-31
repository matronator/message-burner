// import * as clipboard from "clipboard-polyfill";

document.addEventListener(`DOMContentLoaded`, () => {
    const messageLink = document.getElementById(`messageLink`);
    if (messageLink) {
        messageLink.addEventListener(`click`, copyLink);

        function copyLink() {
            try {
                navigator.clipboard.writeText(messageLink.value);
                navigator.clipboard.readText().then(val => {
                    if (!val.includes("burner.matronator.cz/message/new/") && !val.includes("burner.matronator.cz/zprava/nova/")) {
                        copyPolyfill();
                    }
                });
                console.log("Coppied with clipboard API");
            } catch (e) {
                copyPolyfill(e);
            }
            messageLink.focus();
            messageLink.select();
        }

        function copyPolyfill(exception = null) {
            if (exception) {
                console.warn(exception);
            }
            try {
                clipboard.writeText(messageLink.value);
                clipboard.readText().then(val => {
                    if (!val.includes("burner.matronator.cz/message/new/") && !val.includes("burner.matronator.cz/zprava/nova/")) {
                        copyOld();
                    }
                });
                console.log("Coppied with polyfill");
            } catch (e) {
                copyOld(e);
            }
        }

        function copyOld(exception = null) {
            if (exception) {
                console.warn(exception);
            }
            try {
                messageLink.focus();
                messageLink.select();
                document.execCommand("copy", false);
                console.log("Coppied with execCommand");
            } catch (err) {
                consoloe.warn(err);
            }
        }
    }
})
