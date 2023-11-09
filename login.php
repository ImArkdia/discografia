<?php
    $disco;
    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
    }

    $info = "";

    if(isset($_POST["user"]) && isset( $_POST["password"])){
        $prepared = $disco->prepare("SELECT * FROM tabla_usuarios WHERE usuario=:usuario");
        $prepared->execute(array(":usuario" => $_POST["user"]));

        if(($result = $prepared->fetch(PDO::FETCH_ASSOC)) != null) {
            $pass = $result["password"];

            var_dump($pass);
            var_dump($_POST["password"]);
            if(password_verify($_POST["password"], $pass)) {
                $info = "Contraseña y usuario introducidos válido.";
            }else{
                $info = "El usuario o contraseña proporcionados no son válidos";
            }
        }else{
            $info = "No se ha encontrado el usuario proporcionado.";
        }

        echo "
        <h1>Login</h1>
        <form action='#' method='POST'>
            <label for='user'>Usuario: </label>
            <input type='text' name='user' id='user'><br>
            <label for='password'>Contraseña</label>
            <input type='password' name='password' id='password'><br><br>
            <input type='submit' name='enviar' id='enviar'><br>
            ". $info ."
        </form>
        ";
    }else{
        echo "
            <h1>Login</h1>
            <form action='#' method='POST'>
                <label for='user'>Usuario: </label>
                <input type='text' name='user' id='user'><br>
                <label for='password'>Contraseña</label>
                <input type='password' name='password' id='password'><br><br>
                <input type='submit' name='enviar' id='enviar'><br>
                ". $info ."
            </form>
        ";
    }
?>