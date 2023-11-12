<?php
    session_start();

    if(isset($_GET["cerrarSesion"])){
        session_destroy();
        header("Location: ./login.php");
        exit();
    }

    $iniciadoSesion = false;

    $disco;
    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
        exit();
    }

    if(isset($_SESSION["token"]) && isset($_SESSION["user"])){
        $prepared = $disco->prepare("SELECT * FROM tabla_usuarios WHERE usuario=:usuario AND password=:password");
        $prepared->execute(array(":usuario" => $_SESSION["user"], ":password" => $_SESSION["token"]));

        if(($result = $prepared->fetch(PDO::FETCH_ASSOC)) != null) {
            $iniciadoSesion = true;
        }else{
            header("Location: ./login.php");
            exit();
        }
    }else{
        header("Location: ./login.php");
        exit();
    }

    echo '<!DOCTYPE HTML>
        <html>
        <link rel="stylesheet" href="./css/style.css">
    ';


    $usuario1 = "jordi";
    $pass1 = "hola1";

    $usuario2 = "pedro";
    $pass2 = "hola2";

    $usuario3 = "fran";
    $pass3 = "hola3";

    $usuario4 = "marcos";
    $pass4 = "hola4";

    $pass1 = password_hash($pass1, PASSWORD_DEFAULT);
    $pass2 = password_hash($pass2, PASSWORD_DEFAULT);
    $pass3 = password_hash($pass3, PASSWORD_DEFAULT);
    $pass4 = password_hash($pass4, PASSWORD_DEFAULT);

    $prepared = $disco->prepare("INSERT INTO tabla_usuarios (usuario, password) VALUES(:usuario, :password)");
    $prepared->execute(array(":usuario" => $usuario1, ":password" => $pass1));

    $prepared = $disco->prepare("INSERT INTO tabla_usuarios (usuario, password) VALUES(:usuario, :password)");
    $prepared->execute(array(":usuario" => $usuario2, ":password" => $pass2));

    $prepared = $disco->prepare("INSERT INTO tabla_usuarios (usuario, password) VALUES(:usuario, :password)");
    $prepared->execute(array(":usuario" => $usuario3, ":password" => $pass3));

    $prepared = $disco->prepare("INSERT INTO tabla_usuarios (usuario, password) VALUES(:usuario, :password)");
    $prepared->execute(array(":usuario" => $usuario4, ":password" => $pass4));

    header("Location: ./index.php");
    exit();
?>