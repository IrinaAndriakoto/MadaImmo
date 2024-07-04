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
        .biens-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .bien {
            width: 40%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .bien-info {
            margin-bottom: 10px;
        }
        .carousel-container {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }
        .carousel-slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-slide {
            min-width: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            position: absolute;
            top: 0;
            left: 0;
        }

        .carousel-slide.active {
            opacity: 1;
        }
        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .carousel-prev, .carousel-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        .carousel-prev {
            left: 10px;
        }
        .carousel-next {
            right: 10px;
        }
    </style>
</head>
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
                <br>
                
                <h2>La liste des biens</h2>
                <div class="biens-container">
                    <?php
                    $currentBienId = null;
                    $images = [];
                    foreach ($biens as $bien):
                        if ($currentBienId !== $bien->id) {
                            if ($currentBienId !== null) {
                                echo '</div><button class="carousel-prev">&#10094;</button><button class="carousel-next">&#10095;</button></div></div>';
                            }
                            $currentBienId = $bien->id;
                            $images = [];
                            ?>
                            <div class="bien">
                                <div class="bien-info">
                                    <h2><?php echo $bien->nom; ?></h2>
                                    <h6><?php echo $bien->reference; ?></h6>
                                    <p><strong>Type:</strong> <?php echo $bien->type; ?></p>
                                    <p><strong>Description:</strong> <?php echo $bien->description; ?></p>
                                    <p><strong>Lieu:</strong> <?php echo $bien->region; ?></p>
                                    <p><strong>Loyer par mois:</strong> Ar <?php echo $bien->loyer; ?></p>
                                    <p><strong>Prochaine date disponible : </strong> <?php echo $bien->datefin; ?></p>
                                </div>
                                <div class="carousel-container">
                                    <div class="carousel-slides">
                            <?php
                        }
                        ?>
                        <div class="carousel-slide <?php echo empty($images) ? 'active' : ''; ?>">
                            <img src="../../assets/img/<?php echo $bien->url; ?>" alt="Image du bien">
                        </div>
                        <?php
                        $images[] = $bien->url;
                    endforeach;
                    if ($currentBienId !== null) {
                        echo '</div><button class="carousel-prev">&#10094;</button><button class="carousel-next">&#10095;</button></div></div>';
                    }
                    ?>
                </div>
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
                        <li><a href="<?php echo site_url('Welcome/admin') ?>">Retourner à la page d'accueil</a></li>
                        <li><a href="<?php echo site_url('Welcome/logout') ?>">Se Deconnecter</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>

    <script>
        function initializeCarousel() {
    const carousels = document.querySelectorAll('.carousel-container');

    carousels.forEach(carousel => {
        const slides = carousel.querySelectorAll('.carousel-slide');
        const prevButton = carousel.querySelector('.carousel-prev');
        const nextButton = carousel.querySelector('.carousel-next');
        let currentSlide = 0;

        function showSlide(n) {
            slides.forEach((slide, index) => {
                if (index === n) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        prevButton.addEventListener('click', prevSlide);
        nextButton.addEventListener('click', nextSlide);

        // Set initial state
        slides.forEach((slide, index) => {
            if (index === currentSlide) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
            }
        });

        // Auto-play (optional)
        setInterval(nextSlide, 3000); // Change slide every 5 seconds
    });
}

// Call the function to initialize carousels
document.addEventListener('DOMContentLoaded', initializeCarousel);

        initializeCarousel();
    </script>
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/browser.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/breakpoints.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/util.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
</body>
</html>

<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/browser.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/breakpoints.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/util.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
	</body>
 
</html>