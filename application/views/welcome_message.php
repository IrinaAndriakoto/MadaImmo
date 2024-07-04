<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>MADA-IMMO</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/logo.png'); ?>" type="image/x-icon">
	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		/* background-color: #FFEBD2; */
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
		/* background-image: url('../../assets/img/logorun.png');
		background-repeat: no-repeat;
		background-size: contain; */
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
		/* text-align:left; */
	}

	p {
		margin: 0 0 10px;
		padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
		margin-top: 50px;
		text-align:center;
		/* background-color: #273248; */
	}

	.login-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.login-image {
    width: 35%;
    padding-right: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-form {
    width: 60%;
}
.login-image img {
    max-width: 100%;
    max-height: 200px; /* Ajustez cette valeur selon vos besoins */
    width: auto;
    height: auto;
    object-fit: contain;
}

#body input[type="text"], 
#body input[type="password"] {
    width: 100%;
}

/* Styles pour les boutons */
#body .btn {
    width: 100%;
    margin-bottom: 10px;
}

#body form {
    margin-bottom: 10px;
}


/*overlay */
.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
}

.overlay-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 5px;
    position: relative;
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close-btn:hover,
.close-btn:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.overlay-content h2 {
    margin-bottom: 20px;
}

.overlay-content form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.overlay-content form button {
    width: 100%;
    padding: 10px;
}
	</style>
</head>
<body>

<div id="container">
    <h1>MADA-IMMO</h1>
    <div id="body">
    <h5>Veuillez insérer vos informations ci-dessous :</h5><br>
    <div class="login-container">
		<div class="login-image">
				<img src="<?php echo base_url('assets/img/logo.png') ?>" alt="Image de connexion">
			</div>
        <div class="login-form">
            <form action="<?php echo site_url('Welcome/authenticate'); ?>" method="post">
                <p><input type="text" name="nom" placeholder="Votre login" class="form-control" value="admin"></p>
                <br>
                <p><input type="password" name="motdepasse" placeholder="Votre mot de passe" class="form-control" value="admin123"></p>
                <br>
                <button class="btn btn-outline-primary">Se connecter</button>
            </form>
            <button id="showOverlay" class="btn btn-outline-dark">Se connecter en tant que client ou proprietaire</button>
        </div>
        
    </div>
</div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds.</p>
</div>

<div id="overlay" class="overlay">
    <div class="overlay-content">
        <span class="close-btn">&times;</span>
        <h2>Client</h2>
        <form action="<?php echo site_url('Welcome/auth_client'); ?>" method="post">
            <input type="text" name="login" placeholder="Numero de telephone ou email" required>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById('overlay');
    var showOverlayBtn = document.getElementById('showOverlay');
    var closeBtn = document.getElementsByClassName('close-btn')[0];

    showOverlayBtn.onclick = function() {
        overlay.style.display = 'block';
    }

    closeBtn.onclick = function() {
        overlay.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == overlay) {
            overlay.style.display = 'none';
        }
    }
});
</script>
</body>
</html>
