<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter la location</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/main.css'); ?>" />
    <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/chart.js'); ?>"></script>
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/logo.png'); ?>" type="image/x-icon">

    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Hauteur minimale pour que le contenu soit centré verticalement */
            padding: 20px;
            box-sizing: border-box;
        }
        .form-wrapper {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Limite la largeur du formulaire */
        }
        /* Styles pour l'overlay */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            display: none; /* Masqué par défaut */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        #overlayMessage {
            background: #333;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Ajouter la location</h2>
            <form id="locationForm" action="<?php echo site_url('Traitement/insertLocation'); ?>" method="post">
                <label for="client">Le client</label>
                <select name="client" id="">
                    <option value="">Client</option>
                    <?php foreach($locations as $lo): ?>
                    <option value="<?php echo $lo->id ?>"><?php echo $lo->login; ?></option>
                    <?php endforeach; ?>
                </select> <br>
                <label for="nom">Le bien</label>
                <select name="nom" id="">
                    <option value="">Bien</option>
                    <?php foreach($biens as $lo): ?>
                    <option value="<?php echo $lo->id; ?>"><?php echo $lo->nom; ?></option>
                    <?php endforeach; ?>
                </select> <br>
                <label for="duree">La durée de location</label>
                <input type="text" name="duree">
                <label for="date">Date de début</label>
                <input type="date" name="datedebut">
                <br> <br>
                <input type="submit" value="Ajouter">
            </form>
            <a href="<?php echo site_url('Welcome/admin'); ?>">Retourner à la page d'accueil.</a>
        </div>
    </div>

    <div id="overlay">
        <div id="overlayMessage"></div>
    </div>

    <script>
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
    </script>
</body>
</html>
