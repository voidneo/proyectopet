<?php

error_reporting(-1);
ini_set('display_errors', 'On');
set_error_handler("var_dump");


$iid = 0;
$uid = 0;
$user = isset($data["user"]) ? $data["user"] : 0;

// If not logged in, out
if (!$user) {
    header("Location: ./../");
}

// If no IID, out
if (isset($_GET["iid"])) {
    $iid = $_GET["iid"];
} else {
    echo "Error: missing IID";
    return;
};

// If no UID, out
if (isset($_GET["uid"])) {
    $uid = $_GET["uid"];
} else {
    echo "Error: missing UID";
    return;
};

$ctrl = new Controller;
$ctrl->load_model("Inscripcion");

$insc = (new Inscripcion)->findById($iid);
$uci = $insc->getCiUsuario();

// If not your UID and you're not admin, out
if ($uci != $user->getCi() && $user->getRol() == "e") {
    echo "Acceso denegado";
    return;
}

$curs = $insc->getCurso();
$mats = $curs->getMaterias();
$scrs = $insc->getNotas();

$name    = $user->getNombre();
$surname = $user->getApellido();
$cname   = $curs->getNombre();
$period  = $insc->getPeriodo();
$ptype   = rtrim(substr($curs->getDuracion(), 2), "s");
$finsc  = $insc->getAno();
$finsc = (new DateTime($finsc));
$finsc = $finsc->format("d") . "/" . $finsc->format("m") . "/" . $finsc->format("Y");

switch ($period) {
    case 1:
        $period = "Primer";
        break;
    case 2:
        $period = "Segundo";
        break;
    case 3:
        $period = "Tercer";
        break;
    case 4:
        $period = "Cuarto";
        break;
    case 5:
        $period = "Quinto";
        break;
    case 6:
        $period = "Sexto";
        break;
    case 7:
        $period = "S&eacute;ptimo";
        break;
    case 8:
        $period = "Octavo";
        break;
}

/*(new Controller)->load_lib("fpdf/fpdf");


Nope, FPDF no funciona


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output();*/

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "$cname, $name, $surname - Polo educativo tecnologico Durazno"; ?></title>
    <style>
        .b-2 {
            border: 2px solid black;
        }

        .b-b {
            border-bottom: 2px solid black;
        }

        .n {
            font-weight: normal;
        }

        th {
            padding: 1em;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .flex {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 0.5em 1em;
        }

        tbody tr:first-child>td .flex {
            margin-top: 1em;
        }

        tbody tr:last-child>td .flex {
            margin-bottom: 1em;
        }

        .spacer {
            flex-grow: 1;
            border-bottom: 2px dotted black;
        }
    </style>

</head>

<body style="padding: 4em">
    <table class="b-2" style="width: 100%">
        <thead>
            <tr>
                <th colspan="12" class="b-b">Escolaridad</th>
            </tr>
            <tr>
                <th colspan="12" class="text-left b-b">
                    Curso: <?php echo "<span class='n'>$cname</span><br>"; ?>
                    Periodo: <?php echo "<span class='n'>$period $ptype</span><br>"; ?>
                    Estudiante: <?php echo "<span class='n'>$name $surname</span><br>"; ?>
                    Fecha de inscripci&oacute;n: <?php echo "<span class='n'>$finsc</span>"; ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($mats as $m) {
                $mname  = $m->getNombre();
                $mscore = $scrs[$m->getNombre()]->getNota();
                echo "<tr>";
                echo "<td colspan='12'>";
                echo "<div class='flex'>";
                echo "<span>$mname</span>";
                echo "<div class='spacer'></div>";
                echo "<span>$mscore</span>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>