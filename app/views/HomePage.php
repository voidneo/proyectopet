<h3>Homepage</h3>
<?php

if(isset($data["user"])) {
    echo "<h4>Logged in as " . $data["user"]->get_name() . "</h4>";
}

var_dump($data); ?>