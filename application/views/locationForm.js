console.log('JavaScript chargé et prêt à fonctionner.');

document.getElementById('locationForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission normale du formulaire

    var form = document.getElementById('locationForm');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            var overlay = document.getElementById('overlay');
            var overlayMessage = document.getElementById('overlayMessage');
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response);
                    overlayMessage.innerHTML = response.message;
                } catch (e) {
                    overlayMessage.innerHTML = 'Erreur de traitement de la réponse JSON';
                }
            } else {
                overlayMessage.innerHTML = 'Erreur: ' + xhr.status + ' ' + xhr.statusText;
            }
            overlay.style.display = 'flex'; // Afficher l'overlay

            // Masquer l'overlay après 3 secondes
            setTimeout(function() {
                overlay.style.display = 'none';
            }, 3000);
        }
    };

    var formData = new FormData(form);
    var encodedData = new URLSearchParams(formData).toString();

    xhr.send(encodedData);
});
