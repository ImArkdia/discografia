<?php
    $disco;
    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
    }

    session_start();

    $info = "";
    $registroOk = "";

    if(isset($_GET["registrado"])){
        $registroOk = "Usuario registrado correctamente.";
    }

    if(isset($_GET["userError"]) && $_GET["userError"] == true){
        $info = "El usuario o contraseña proporcionados no son válidos";
    }else if(isset($_GET["userError"]) && $_GET["userError"] == false){
        $info = "No se ha encontrado el usuario proporcionado.";
    }

    if(isset($_POST["user"]) && isset( $_POST["password"])){
        $prepared = $disco->prepare("SELECT * FROM tabla_usuarios WHERE usuario=:usuario");
        $prepared->execute(array(":usuario" => $_POST["user"]));

        if(($result = $prepared->fetch(PDO::FETCH_ASSOC)) != null) {
            $pass = $result["password"];

            if(password_verify($_POST["password"], $pass)) {
                $_SESSION["token"] = $pass;
                $_SESSION["user"] = $_POST["user"];
                header("Location: ./index.php");
                exit();
            }else{
                header("Location: ./login.php?userError=true");
                exit();
            }
        }else{
            header("Location: ./login.php?userError=false");
            exit();
        }
    }else{
        echo "
        <h1>Login</h1>
        <form action='#' method='POST'>
            <label for='user'>Usuario: </label>
            <input type='text' name='user' id='user'><br>
            <label for='password'>Contraseña</label>
            <input type='password' name='password' id='password'><br><br>
            <input type='submit' name='enviar' id='enviar'><br><br>
            ". $info ."
            ". $registroOk ."
        </form>
        <div><a href='./registro.php'>Click para Registrarte</a></div>
        ";
    }
?>