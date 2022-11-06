<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polo educativo tecnologico</title>
    <link href="./css/all.min.css" rel="stylesheet" />
    <link href="./css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="./scripts/popper.min.js"></script>
    <script type="text/javascript" src="./scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="./scripts/bb.js"></script>
    <script type="text/javascript" src="./scripts/elements.js"></script>
    <script type="text/javascript" src="./scripts/home-page.js"></script>
    <style>
        #news-results > a, #announcements-results > a, #jobs-results > a {
            text-decoration: none;
        }

        #news-results h5, #announcements-results h5, #jobs-results h5,
        #news-results p.card-text,
        #announcements-results p.card-text,
        #jobs-results p.card-text {
            color: rgb(33, 37, 41) !important;
        }
    </style>
</head>

<body>
    <input type="hidden" value="<?php echo $_SESSION["security_hash"]; ?>" id="security-hash" />
    <?php
    $ctrl = new Controller;
    $user = "usuario";

    $ctrl->load_view("components/Header", $data);

    if (isset($_SESSION["token"])) {
        $user = $data["nombre"];
    }

    ?>
    
    <main>
        <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
            <div class="col-md-8 p-lg-5 mx-auto my-8">
                <h1 class="display-4 fw-normal">Bienvenido <?php echo $user; ?></h1>
                <p class="lead fw-normal">Al sitio web oficial del Polo educativo tecnologico Durazno, lugar para aquellos individuos que desean llevar su educacion al pr&oacute;ximo nivel.</p>
                <a class="btn btn-outline-primary" href="./cursos">Estudia con nosotros</a>
            </div>
            <div class="product-device shadow-sm d-none d-md-block"></div>
            <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
        </div>
    </main>

    <div class="container">
        <h1>&Uacute;ltimas noticias</h1>
        <div class="row" id="news-results" style="margin: 2em auto;">
        </div>
    </div>

    <div class="container">
        <h1 style="text-align: right;">Anuncios recientes</h1>
        <div class="row" id="announcements-results" style="margin: 2em auto;">
        </div>
    </div>

    <div class="container">
        <h1>Ofertas laborales</h1>
        <div class="row" id="jobs-results" style="margin: 2em auto;">
        </div>
    </div>
</body>

</html>