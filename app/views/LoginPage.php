<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title><?php echo $data["title"]; ?></title>
    <link href="./css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="./scripts/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, Sans-serif;
            background-color: #f2f2f2;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
        }
    </style>
</head>

<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form id="form" action="login.php" method="POST">
            <h2 class="h3 mb-3 fw-normal">Iniciar sesion</h2>
            <div class="form-floating mb-3">
                <input type="tel" name="cedula" class="form-control" id="cedula" placeholder="12345678" pattern="^[0-9]{8}">
                <label for="cedula">C&eacute;dula</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="contrasena" placeholder="Contrasena">
                <label for="contrasena">Contrase&ntilde;a</label>
            </div>
            <input type="hidden" id="security_hash" name="security_hash" value="<?php echo $data["security_hash"] ?>" />
            <div>
                <br>
                <p id="feedback" class="text-danger"></p>
            </div>
            <button id="enviar" type="submit" class="w-100 btn btn-lg btn-primary">Iniciar sesi&oacute;n</button>
        </form>
    </main>
    <script type="text/javascript" src="./scripts/login-form.js"></script>
</body>

</html>