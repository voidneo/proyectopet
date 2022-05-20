<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title><?php echo $data["title"]; ?></title>
    <style>
        body {
            font-family: Arial, Sans-serif;
            background-color: #f2f2f2;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrapper {
            background-color: white;
            border-radius: 5px;
            padding: 25px;
            width: 300px;
            box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.12);
        }

        input[type=text],
        input[type=password] {
            border: none;
            width: -moz-available;
            outline: none;
            padding: 10px;
            margin: 0px;
            margin-top: 10px;
            color: #828282;
            font-size: 16px;
            font-family: Sans-serif;
        }

        input[type=submit] {
            border: none;
            width: -moz-available;
            outline: none;
            background-color: #4DA3EE;
            border-radius: 5px;
            height: 40px;
            color: white;
            font-family: Sans-serif;
            font-size: 16px;
            margin-top: 10px;
        }

        input[type=submit]:hover {
            background-color: #64B7FF;
        }

        input[type=submit]:active {
            background-color: #408FD5;
        }

        .border {
            height: 2px;
            background-color: #4DA3EE;
            width: 0%;
            transition: width 0.3s ease;
            margin-bottom: 5px;
        }

        .field:focus-within>.border {
            width: 100%;
        }

        .title {
            margin-top: 0px;
            text-align: center;
            color: #464646;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <form id="form" action="login.php" method="POST">
            <h1 class="title"><?php echo $data["title"]; ?></h1>
            <div class="field">
                <label>Cedula de identidad</label><br />
                <input id="ci" name="ci" type="text" placeholder="Cedula sin puntos ni guioes" /></br>
                <div class="border"></div>
            </div>
            <div class="field">
                <label>Correo elecrt&oacute;nico</label><br />
                <input id="correo" name="correo" type="text" placeholder="correo@dominio.com" /></br>
                <div class="border"></div>
            </div>
            <div class="field">
                <label>Nombre</label><br />
                <input id="nombre" name="nombre" type="text" placeholder="Primer nombre" /></br>
                <div class="border"></div>
            </div>
            <div class="field">
                <label>Apellido</label><br />
                <input id="apellido" name="apellido" type="text" placeholder="Primer apellido" /></br>
                <div class="border"></div>
            </div>
            <div class="field">
                <label>Contrasena</label></br>
                <input id="pass" name="pass" type="password" placeholder="●●●●●●●●●●●●" /><br />
                <div class="border"></div>
            </div>
            <input type="hidden" id="security_hash" name="hash" value="<?php echo $data["security_hash"] ?>" />
            <div>
                <p id="feedback"></p>
            </div>
            <input id="enviar" type="submit" value="Registrarse" />
        </form>
    </div>
</body>

</html>