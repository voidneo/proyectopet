<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data["article"]->getTitulo() ?></title>
</head>
<body>
    <h1>
        <?php echo $data["article"]->getTitulo() ?>
    </h1>
    <p>
        <?php echo $data["article"]->getCuerpo() ?>
    </p>
    <p> Fecha de publicacion: 
        <?php echo (new DateTime($data["article"]->getFecha()))->format("d/m/Y") ?>
    </p>
</body>
</html>