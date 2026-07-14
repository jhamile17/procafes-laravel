document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('btnResend');
    const counter = document.getElementById('countdown');
    if (!button || !counter) {
        return;
    }
    let seconds = parseInt(button.dataset.seconds, 10);
    if (isNaN(seconds)) {
        seconds = 60;
    }
    counter.textContent = seconds;
    const timer = setInterval(() => {
        seconds--;
        counter.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(timer);
            button.disabled = false;
            button.innerHTML = 'Reenviar correo';
        }
    }, 1000);
});