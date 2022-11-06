<?php
$tab = isset($_GET["tab"]) ? $_GET["tab"] : "users";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet" />
    <!-- font awesome -->
    <link href="./css/all.min.css" rel="stylesheet" />
    <script type="text/javascript" src="./scripts/popper.min.js"></script>
    <script type="text/javascript" src="./scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="./scripts/elements.js"></script>
    <style>
        #sidebar-wrapper {
            padding-right: 0px;
        }

        #sidebar-title {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }

        .form-control:read-only {
            background-color: #e9ecef;
            opacity: 1;
        }

        #of-fix {
            max-width: 100%;
        }

        #component {
            max-width: 100%;
            overflow-x: scroll;
        }

        .adscripto {
            color: #52864e !important;
            /*text-shadow: -1px 0px 0px #972ed2, 1px 0px 0px #972ed2, 0px -1px 0px #972ed2, 0px 1px 0px #972ed2;*/
        }

        .docente {
            color: #2e6ed2 !important;
        }

        .invalid,
        .invalid:hover>td {
            color: #845a0b !important;
        }

        .valid,
        .invalid:hover>td {
            color: #606060 !important;
        }

        .u>span {
            text-decoration: underline;
            color: black;
        }
    </style>
</head>

<body>
    <?php (new Controller)->load_view("components/Header", $data); ?>
    <div class="row d-flex" id="of-fix">
        <div class="border-end bg-white col-xl-2" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light" id="sidebar-title">Panel de control</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./controlpanel?tab=users&std=1&tch=1&adm=1" id="btn-users">
                    <i class="fa-solid fa-users"></i> Usuarios
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#" id="btn-categories">
                    <i class="fa-solid fa-list-ul"></i> Categorias
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#" id="btn-articles">
                    <i class="fa-solid fa-file-lines"></i> Articulos
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./controlpanel?tab=subjects" id="btn-articles">
                    <i class="fa-solid fa-book"></i> Materias
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./controlpanel?tab=courses" id="btn-articles">
                    <i class="fa-solid fa-graduation-cap"></i> Cursos
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./controlpanel?tab=inscriptions" id="btn-articles">
                    <i class="fa-solid fa-file-pen"></i> Inscripciones
                </a>
            </div>
        </div>
        <div id="page-content" class="container-fluid col-xl-10">
            <div id="component">
                <?php
                $c = new Controller;
                switch ($tab) {
                    case "subjects":
                        $c->load_view("components/SubjectManager");
                        break;
                    case "courses":
                        $c->load_view("components/CourseManager");
                        break;
                    case "inscriptions":
                        $c->load_view("components/InscriptionManager");
                        break;
                    default:
                        $c->load_view("components/UserManager");
                        break;
                }
                ?>
            </div>
            <div>
                <input type="hidden" id="security-hash" value="<?php echo $data["security_hash"] ?>" />
                <script type="text/javascript" src="./scripts/control-panel.js"></script>
            </div>
        </div>
    </div>
</body>

</html>