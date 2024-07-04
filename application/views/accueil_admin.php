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
        width: 500px;
        height: 300px;
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
                    <a href="<?php echo site_url('Welcome/admin') ?>" class="logo"><strong>Mada Immo.</strong></a>
                </header>
                <br> <br>
                <div class="cnv">
                    <h4 style='text-decoration: underline;'>Tableau de bord</h4> <br>
                    <canvas id="myChart" width="500" height="250"></canvas>
                            <br> 
                    <a href="<?php echo site_url('Traitement/populate_details') ?>"><button>Generer locations-details</button></a>
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
                    <header class="major">
                        <h2>Menu</h2>
                    </header>
                    <ul>
                        <li><a href="<?php echo site_url('Traitement/listeBiens') ?>">Liste des biens</a></li>
                        <li><a href="#" onclick="loadPage('<?php echo site_url('Traitement/chiffre_admin'); ?>')">Monthly revenue</a></li>
                        <li><a href="<?php echo site_url('Traitement/location'); ?>">Ajouter location</a></li>
                        <li><a href="#" onclick="loadPage('<?php echo site_url('Traitement/getAllLocations'); ?>')">Liste location</a></li>
                        <li><a href="#" onclick="loadPage('<?php echo site_url('Traitement/importations'); ?>')">Importation</a></li>
                        <li><a href="<?php echo site_url('DatabaseController/reset_database') ?>">Reinitialiser la base de données</a></li>
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
                    // Initialize the carousel after the new content is loaded
                    if (typeof initializeCarousel === 'function') {
                        initializeCarousel();
                    }
                }
            };
            xhr.send();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Initialize carousels if there are any on the initial load
            if (typeof initializeCarousel === 'function') {
                initializeCarousel();
            }
        });

       // Function to load rental data
       function loadRentalData() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '<?php echo site_url('Traitement/getRentalStats'); ?>', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    createChart(data);
                }
            };
            xhr.send();
        }

// Function to create the chart


function createChart(data) {
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Nombre de locations',
                    data: data.counts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-locations'
                },
                {
                    label: 'Gains (en Ar)',
                    data: data.gains,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-gains'
                }
            ]
        },
        options: {
            scales: {
                'y-locations': {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de locations'
                    },
                    ticks: {
                        stepSize: 1
                    },
                    position: 'left'
                },
                'y-gains': {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Gains (en Ar)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' Ar';
                        }
                    },
                    position: 'right'
                },
                x: {
                    title: {
                        display: true,
                        text: 'Mois'
                    }
                }
            },
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Nombre de locations et gains par mois'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y;
                                if (context.dataset.label === 'Nombre de locations') {
                                    label += ' location(s)';
                                } else {
                                    label += ' Ar';
                                }
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}


// Call the function to load data and create the chart
loadRentalData();
    </script>
			<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/browser.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/breakpoints.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/util.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
	</body>
 
</html>