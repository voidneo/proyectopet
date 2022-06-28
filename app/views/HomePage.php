<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="./scripts/jeiQuery-100-real-no-fake.js"></script>
</head>

<body>
    <h3 id="a">Homepage</h3>
    <?php

    if (isset($data["token"])) {
        echo "<h4>Bienvenido " . $data["nombre"] . " " . $data["apellido"] . "</h4>";
        echo "<a href='#'>Panel de control</a> ";
        echo "<a href='./logout'>Cerrar sesion</a>";
    } else {
        echo "<h4>Bienvenido usuario</h4>";
        echo "<a href='./signup'>Registrarse</a> or ";
        echo "<a href='./login'>iniciar sesi&oacute;n</a>";
    }

    ?>
</body>
</html>