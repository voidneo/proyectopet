<?php

if (isset($_SESSION["token"])) {
    (new Controller())->load_view("components/UserMenuLoggedIn", $data);
} else {
    (new Controller())->load_view("components/UserMenuLoggedOut", $data);
}

?>