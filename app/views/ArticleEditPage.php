<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar articulo</title>
    <link href="./css/all.min.css" rel="stylesheet" />
    <link href="./css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="./scripts/popper.min.js"></script>
    <script type="text/javascript" src="./scripts/bootstrap.min.js"></script>
    <script type="text/javascript" src="./scripts/bb.js"></script>
    <style>
        .hidden { display: none !important; }
        #btn-stop-preview { border-radius: 0.375rem 0rem 0rem 0.375rem; }
    </style>
</head>
<body>
    <?php
    $ctrl = new Controller;
    $ctrl->load_view("components/Header", $data);
    $ctrl->load_view("components/ArticleEditor", $data);
    ?>
</body>
</html>