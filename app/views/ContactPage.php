<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polo educativo tecnologico</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet" />
    <!-- font awesome -->
    <link href="./css/all.min.css" rel="stylesheet" />
    <script type="text/javascript" src="./scripts/popper.min.js"></script>
    <script type="text/javascript" src="./scripts/bootstrap.min.js"></script>
    <style>
        .form-row {
            display: flex;
            gap: 0.5em;
            margin: 1em auto;
        }

        .form-row .form-group {
            flex-grow: 1;
        }

        .flex {
            display: flex;
        }

        .social {
            font-size: 3em;
            justify-content: space-around;
            margin-top: 0.5em;
        }

        .column {
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container,
        .row,
        .margin {
            margin: 2em auto;
        }

        p.margin a {
            color: var(--color-gray);
        }

        .social-btn:hover>.fa-facebook-f {
            color: #3b5998;
        }

        h1 {
            margin-bottom: 0.5em;
        }

        .social-btn:hover>.fa-instagram {
            background-image: linear-gradient(45deg, #FCAF45, #E1306C, #C13584, #833AB4, #5851DB);
            background-size: 100%;
            -webkit-background-clip: text;
            -moz-background-clip: text;
            -webkit-text-fill-color: transparent;
            -moz-text-fill-color: transparent;
        }

        .social-btn:hover>.fa-linkedin-in {
            color: #0077b5;
        }
    </style>
</head>

<body>
    <?php
    $ctrl = new Controller;
    $ctrl->load_view("components/Header", $data);
    ?>
    <div class="container">
        <div class="row">
            <div class="col flex column">
                <form action="./sendmail" method="POST">
                    <!-- TODO: add processing address -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="surname">Apellido</label>
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="Apellido" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Correo electr&oacute;nico</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Correo electronico" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="subject">Asunto</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Asunto" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="message">Mensaje</label>
                            <textarea class="form-control" id="message" name="message" required></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <input type="submit" class="btn btn-primary" id="submitBtn" value="Enviar" />
                    </div>
                </form>
            </div>
            <div class="col">
                <div class="container">
                    <h1>Contactanos</h1>
                    <p>Un mensaje extraordinariamente amigable de como podes contactarnos a traves de diferentes medios.</p>
                    <div class="flex social">
                        <a href="#" class="text-secondary social-btn" target="_BLANK" title="Compartir en facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-secondary social-btn" target="_BLANK" title="Compartir en instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="text-secondary social-btn" target="_BLANK" title="Compartir en linkedin">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    </div>
                    <p class="margin text-secondary">
                        Telefono: 0000 0000
                        <br>
                        Correo electr&oacute;nico: <a href="mailto:petd@utu.edu.uy">petd@utu.edu.uy</a>
                        <br>
                        Direcci&oacute;n: Maestro Agustin Ferreiro entre 4 de octubre y Dr. Miguel C. Rubino
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <hr>
        </div>
        <div class="row">
            <h1>C&oacute;mo llegar</h1>
            <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=-56.51821821928024%2C-33.38901728036876%2C-56.51522487401963%2C-33.38767131876141&amp;layer=mapnik&amp;marker=-33.388345421938396%2C-56.51672154664993" style="border: 1px solid black"></iframe>
            <br />
            <small>
                <a href="https://www.openstreetmap.org/?mlat=-33.38835&amp;mlon=-56.51672#map=19/-33.38834/-56.51672">Abrir en una nueva pesta&ntilde;a</a>
            </small>
        </div>
    </div>

    <?php
    if (isset($_GET["success"])) {
        if ($_GET["success"]) {
            echo "<script>alert('El mensaje se envio con exito');</script>";
        } else {
            echo "<script>alert('Hubo un error al enviar el mensaje');</script>";
        }
    }
    ?>

</body>

</html>