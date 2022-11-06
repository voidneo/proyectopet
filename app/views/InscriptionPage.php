<?php

$logged_in = isset($_SESSION["id"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
            echo "Inscripciones - Polo educativo tecnologico Durazno";
            ?></title>
    <link href="./css/all.min.css" rel="stylesheet" />
    <link href="./css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="./scripts/popper.min.js"></script>
    <script type="text/javascript" src="./scripts/bootstrap.min.js"></script>
    <style>
        .hidden {
            display: none !important;
        }

        .flex {
            display: flex;
        }

        .sb {
            justify-content: space-between;
        }

        .v-flex {
            display: flex;
            flex-direction: column;
        }

        .gap {
            gap: 1em;
        }

        .center {
            flex-direction: column;
            justify-content: center;
        }

        .margin {
            margin: 1em auto;
        }

        .side-margin {
            margin: auto 1em;
        }
    </style>
</head>

<body>
    <?php
    $c = new Controller;
    $c->load_view("components/Header", $data);
    $c->load_view("components/InscriptionForm", $data);
    ?>
</body>

</html>