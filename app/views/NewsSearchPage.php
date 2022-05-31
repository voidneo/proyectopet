<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
</head>
<body>
    <ul>
        <?php
        $date;
        foreach($data["news"] as $news) {
            $date = new DateTime($news->getFecha());
            echo "<li><a href='" . $data["base_url"] . $date->format("Y") . "/" . $date->format("m") . "/" . $date->format("d") . "/" . $news->getId() . "'>" . $news->getTitulo() . "</a> - " . $date->format("Y/m/d") . "</li>";
        }
        ?>
    </ul>
</body>
</html>