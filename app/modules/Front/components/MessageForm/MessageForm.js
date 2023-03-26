const sendButton = document.querySelector('.send-msg-button');
const sentButton = document.getElementById('msg-sent-button');

const readSubmit = document.querySelector('.read-msg-button');
const readButton = document.getElementById('read-msg-button');

if (sendButton && sentButton) {
    sendButton.addEventListener('click', function() {
        sendButton.classList.add('hidden');
        sentButton.classList.remove('hidden');
    });
}

if (readSubmit && sentButton) {
    readSubmit.addEventListener('click', function() {
        readSubmit.classList.add('hidden');
        sentButton.classList.remove('hidden');
    });
}

if (sentButton && readButton) {
    readButton.addEventListener('click', function() {
        readButton.classList.add('hidden');
        sentButton.classList.remove('hidden');
    });
}
