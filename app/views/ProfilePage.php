<?php

$imgs = [
    "https://i.pinimg.com/originals/d0/75/53/d07553113d12d51998d70f3667beb97b.jpg",
    "https://yt3.ggpht.com/ytc/AKedOLSxLWxUM_S1Yv4dmM9X9wD1QMxaPqWjUt-8ukJi7g=s900-c-k-c0x00ffffff-no-rj",
    "https://c.tenor.com/wCkrWEC5wJYAAAAM/usadapekora-pekora.gif",
    "https://nitter.unixfox.eu/pic/https%3A%2F%2Fpbs.twimg.com%2Fprofile_images%2F1351431538823467008%2Fb-3EAJVu_400x400.jpg",
    "https://nitter.unixfox.eu/pic/https%3A%2F%2Fpbs.twimg.com%2Fprofile_images%2F1474704981664010240%2FaOE6_zab.jpg",
    "https://media.tenor.com/images/b43dce21728b9598e05f97ff21b12f67/tenor.gif",
    "https://media.tenor.com/images/9f87574714128d81a2971d6727669cc3/tenor.gif",
    "https://avatars.akamai.steamstatic.com/9fd041ec91024e4b83e4b0cbbbb99792976abb20_full.jpg"
];

$img = $imgs[rand(0, count($imgs) - 1)];

$id = $data["user"]->getId();
$doc = $data["user"]->getCi();
$name = $data["user"]->getNombre();
$surname = $data["user"]->getApellido();
$role = $data["user"]->getRol();
$phone = $data["user"]->getTelefono();
$valid = $data["user"]->getValido();
// carrera

switch ($role) {
    case "a":
        $role = "Adscripto";
        break;
    case "d":
        $role = "Docente";
        break;
    case "e":
        $role = "Estudiante";
        break;
    default:
        $role = "Adscripto";
        break;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
            echo "Perfil de $name $surname - Polo educativo tecnologico Durazno";
            ?></title>
    <link href="./<?php echo $data["path_fix"]; ?>css/all.min.css" rel="stylesheet" />
    <link href="./<?php echo $data["path_fix"]; ?>css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="./<?php echo $data["path_fix"]; ?>scripts/popper.min.js"></script>
    <script type="text/javascript" src="./<?php echo $data["path_fix"]; ?>scripts/bootstrap.min.js"></script>
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

        .shadow {
            box-shadow: 0px 0px 5px gray, 0px 0px 20px gray;
        }

        .avatar {
            width: fit-content;
        }

        .avatar img {
            margin: .75em;
            max-width: 184px;
            min-width: 184px;
            max-height: 327px;
        }

        #sidebar-wrapper .list-group {
            width: 15rem;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        .section-title {
            margin-top: 0.5em;
            margin-bottom: 1em;
        }

        .list-item {
            margin: 0.75em 0em;
        }

        .cmw {
            min-width: fit-content;
        }
    </style>
</head>

<body>
    <?php
    (new Controller)->load_view("components/Header", $data);
    ?>
    <div class="container">
        <div class="row margin">
            <div class="col-md-3 flex center">
                <div class="avatar shadow">
                    <img src="<?php echo $img; ?>" />
                </div>
            </div>
            <div class="col-md-9">
                <div class="info margin">
                    <?php
                    echo "<h4>$name $surname</h4>";
                    echo "<h6>$role</h6>";
                    ?>
                </div>
            </div>
        </div>
        <hr>
        <?php (new Controller)->load_view("components/ProfileControlPanel", $data); ?>
    </div>
</body>

</html>