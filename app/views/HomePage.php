<h3>Homepage</h3>
<?php

if(isset($data["token"])) {
    echo "<h4>Bienvenido " . $data["nombre"] . " " . $data["apellido"] . "</h4>";
} else {
    echo "<h4>Bienvenido usuario</h4>";
}

?>