// Demande une confirmation avant l'envoi des formulaires marqués data-confirm
// (évite le recours à des attributs inline type onclick/onsubmit).
document.addEventListener('submit', function (event) {
    var message = event.target.getAttribute('data-confirm');
    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});

// Double curseur du filtre "Année de sortie", synchronisé avec deux champs numériques
// (le curseur seul ne montre pas assez clairement l'année choisie tant qu'on ne l'a pas relâché).
var champAnneeMin = document.getElementById('anneeMin');
var champAnneeMax = document.getElementById('anneeMax');
var texteAnneeMin = document.getElementById('anneeMinTexte');
var texteAnneeMax = document.getElementById('anneeMaxTexte');

function limiterAnnee(valeur, champ) {
    var bas = parseInt(champ.min, 10);
    var haut = parseInt(champ.max, 10);
    if (valeur < bas) {
        valeur = bas;
    }
    if (valeur > haut) {
        valeur = haut;
    }
    return valeur;
}

function ajusterAnneeMin(valeur) {
    valeur = limiterAnnee(valeur, champAnneeMin);
    var max = parseInt(champAnneeMax.value, 10);
    if (valeur > max) {
        valeur = max;
    }
    champAnneeMin.value = valeur;
    texteAnneeMin.value = valeur;
}

function ajusterAnneeMax(valeur) {
    valeur = limiterAnnee(valeur, champAnneeMax);
    var min = parseInt(champAnneeMin.value, 10);
    if (valeur < min) {
        valeur = min;
    }
    champAnneeMax.value = valeur;
    texteAnneeMax.value = valeur;
}

if (champAnneeMin && champAnneeMax && texteAnneeMin && texteAnneeMax) {
    champAnneeMin.addEventListener('input', function () {
        ajusterAnneeMin(parseInt(champAnneeMin.value, 10));
    });
    champAnneeMax.addEventListener('input', function () {
        ajusterAnneeMax(parseInt(champAnneeMax.value, 10));
    });
    texteAnneeMin.addEventListener('change', function () {
        if (!isNaN(parseInt(texteAnneeMin.value, 10))) {
            ajusterAnneeMin(parseInt(texteAnneeMin.value, 10));
        }
    });
    texteAnneeMax.addEventListener('change', function () {
        if (!isNaN(parseInt(texteAnneeMax.value, 10))) {
            ajusterAnneeMax(parseInt(texteAnneeMax.value, 10));
        }
    });
}
