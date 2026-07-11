// Demande une confirmation avant l'envoi des formulaires marqués data-confirm
// (évite le recours à des attributs inline type onclick/onsubmit).
document.addEventListener('submit', function (event) {
    var message = event.target.getAttribute('data-confirm');
    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});
