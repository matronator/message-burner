const sendButton = document.querySelector('.send-msg-button');
const sentButton = document.getElementById('msg-sent-button');

const readSubmit = document.querySelector('.read-msg-button');
const readButton = document.getElementById('read-msg-button');

if (sendButton && sentButton) {
    sendButton.addEventListener('click', function() {
        sendButton.classList.add('hidden');
        sendButton.classList.remove('inline-block');
        sentButton.classList.add('inline-flex');
        sentButton.classList.remove('hidden');
    });
}

if (readSubmit && sentButton) {
    readSubmit.addEventListener('click', function() {
        readSubmit.classList.add('hidden');
        readSubmit.classList.remove('inline-block');
        sentButton.classList.add('inline-flex');
        sentButton.classList.remove('hidden');
    });
}

if (sentButton && readButton) {
    readButton.addEventListener('click', function() {
        readButton.classList.add('hidden');
        readButton.classList.remove('inline-block');
        sentButton.classList.add('inline-flex');
        sentButton.classList.remove('hidden');
    });
}
