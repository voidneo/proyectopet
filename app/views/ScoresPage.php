<?php

$curs = $data["curs"];
$user = $data["user"];
$ctrl = new Controller;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
            echo $curs->getNombre() . ", " . $user->getNombre() . " " . $user->getApellido() . " - Polo educativo tecnologico Durazno";
            ?></title>
    <link href="./<?php echo $data["path_fix"]; ?>css/all.min.css" rel="stylesheet" />
    <link href="./<?php echo $data["path_fix"]; ?>css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="./<?php echo $data["path_fix"]; ?>scripts/popper.min.js"></script>
    <script type="text/javascript" src="./<?php echo $data["path_fix"]; ?>scripts/bootstrap.min.js"></script>
</head>

<body>
    <?php
    $ctrl->load_view("components/Header", $data);
    $ctrl->load_view("components/ScoreEditor", $data);
    ?>
</body>

</html>