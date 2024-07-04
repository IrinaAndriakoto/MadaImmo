<!DOCTYPE HTML>
<html>
<head>
    <title>Homepage</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/main.css'); ?>" />
    <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/chart.js'); ?>"></script>
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/logo.png'); ?>" type="image/x-icon">

</head>
<style>
    .insert {
        display: none;
    }
    .insert.active {
        display: block;
    }
    .insert a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #333;
        margin-bottom: 10px;
        border-radius: 5px;
        background-color: #ddd;
    }
    .insert a.active {
        background-color: #007bff;
        color: #ddd;
    }
    .cnv {
        width: 800px;
        height: 100px;
    }
</style>
<body class="is-preload">

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Main -->
        <div id="main">
            <div class="inner">
                <!-- Header -->
                <header id="header">
                    <a href="<?php echo site_url('Welcome/proprio') ?>" class="logo"><strong>Mada Immo.</strong></a>
                </header>
                <br>
                <div class="cnv">
					<!-- <h4 style='text-decoration: underline;'>Statistique de vente de meuble par genre</h4>
					<canvas id="myChart" width="200" height="100"></canvas> -->
                    <h2>Bienvenu(e)</h2>
                    <h3>Voici les biens en votre nom:</h3>

                    <table>
                        <tr>
                            <th>Reference</th>
                            <th>Type</th>
                            <th>Bien</th>
                            <th>Description</th>
                            <th>Lieu</th>
                            <th>Loyer par mois</th>
                        </tr>
                        <?php foreach($biens as $bien): ?>
                        <tr>
                                <td><?php echo $bien->reference; ?></td>
                                <td><?php echo $bien->type; ?></td>
                                <td><?php echo $bien->nom; ?></td>
                                <td><?php echo $bien->description; ?></td>
                                <td><?php echo $bien->region; ?></td>
                                <td>Ar <?php echo $bien->loyer; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
				</div>
                <!-- Section -->
                <section>
                    <div class="content" id="dynamicContent">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </section>
            </div>
        </div>

        <!-- Sidebar -->
        <div id="sidebar">
            <div class="inner">
                <!-- Search -->
                <section id="search" class="alt">
                    <form method="post" action="#">
                        <input type="text" name="query" id="query" placeholder="Search" />
                    </form>
                </section>
                
                <!-- Menu -->
                <nav id="menu">
                    <h3>Profil Proprietaire.</h3>
                    <header class="major">
                        <h2>Menu</h2>
                    </header>
                    <ul>
                        <li><a href="<?php echo site_url('Traitement/listeBiensProprio');?>">La liste des vos biens</a></li>
                        <li><a href="#" onclick="loadPage('<?php echo site_url('Traitement/chiffre_proprio'); ?>')">Owner's monthly revenue</a></li>
						<li><a href="<?php echo site_url('Welcome/logout') ?>">Se Deconnecter</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        function showContent(tabId) {
            var contentDivs = document.querySelectorAll('.content .insert');
            contentDivs.forEach(function(div) {
                div.classList.remove('active');
            });

            var selectedDiv = document.getElementById(tabId);
            if (selectedDiv) {
                selectedDiv.classList.add('active');
            }
        }

        function loadPage(url) {
            // Hide the chart
            document.querySelector('.cnv').style.display = 'none';

            // Load the page content dynamically
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('dynamicContent').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Static data for chart
        // var data = {
        //     'Hommes': 55,
        //     'Femmes': 45
        // };

        // // GRAPH
        // var labels = Object.keys(data);
        // var datas = Object.values(data);

        // // Créer le graphique
        // var ctx = document.getElementById('myChart').getContext('2d');
        // var myChart = new Chart(ctx, {
        //     type: 'pie',
        //     data: {
        //         labels: labels,
        //         datasets: [{
        //             data: datas,
        //             backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
        //             borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         responsive: true,
        //         title: {
        //             display: true,
        //             text: 'Pourcentage de genre par commande effectuée'
        //         }
        //     }
        // });
    </script>
			<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/browser.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/breakpoints.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/util.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
	</body>
 
</html>