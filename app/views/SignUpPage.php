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

        .form-signup {
            max-width: 330px;
            padding: 15px;
        }
    </style>
</head>

<body class="text-center">
    <main class="form-signup w-100 m-auto">
        <form id="form" action="login.php" method="POST">
            <h1 class="h3 mb-3 fw-normal">Registrarme</h1>
            <div class="form-floating">
                <input type="tel" name="cedula" class="form-control" id="cedula" placeholder="12345678"  pattern="^[0-9]{8}" required>
                <label for="cedula">C&eacute;dula</label>
            </div>
            <div class="form-floating">
                <input type="email" name="correo" class="form-control" id="correo" placeholder="usuario@dominio.com" required>
                <label for="correo">Correo</label>
            </div>
            <div class="form-floating">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" id="nombre" required>
                <label for="nombre">Nombre</label>
            </div>
            <div class="form-floating">
                <input type="text" name="apellido" class="form-control" id="apellido" placeholder="Apellido" required>
                <label for="apellido">Apellido</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="contrasena" placeholder="Contrasena">
                <label for="contrasena">Contrase&ntilde;a</label>
            </div>
            <input type="hidden" id="security_hash" name="security_hash" value="<?php echo $data["security_hash"] ?>" />
            <div>
                <p id="feedback"></p>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" id="enviar">Registrarse</button>
        </form>
    </main>
    <script type="text/javascript" src="./scripts/signup-form.js"></script>
</body>

</html>