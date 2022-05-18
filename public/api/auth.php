<?php
    header('Content-type: application/json; charset=utf-8');

    // TODO: Re introduce security hash
    
    if(!isset($_POST["user"]) || !isset($_POST["pass"]) /*|| !isset($_POST["hash"])*/) {
        echo json_encode(["message" => "Missing essential information"]);
        return;
    }

    $user = $_POST["user"];
    $pass = $_POST["pass"];
    //$hash = $_POST["hash"];
    
    session_start();
    
    // very legit authentication
    if($user == "admin" && $pass == "1234" /*&& $hash == $_SESSION["login_hash"]*/) {
        // TODO: generate a real token
        $token = hash("sha1", random_bytes(8));

        $_SESSION["user"] = $user;
        $_SESSION["token"] = $token;

        echo json_encode(["token" => $token]);
    } else {
        echo json_encode(["message" => "Invalid credentials"]);
    }
?>