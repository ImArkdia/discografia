<?php
    $disco;
    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
    }

    $info = "";
    $cookieOk = false;

    if(isset($_POST["no"])){

    }

    if(isset($_COOKIE["usuario"])){
        $cookieOk = true;
    }

    if(isset($_POST["si"])){
        echo "<p>Acceso correcto<p>";
    }else if(isset($_POST["no"])){ 
        setcookie("usuario","", time()-3600);
        header("Location: ./login.php");
        exit();
    }else if(isset($_POST["user"]) && isset( $_POST["password"]) && $cookieOk == false){
        $prepared = $disco->prepare("SELECT * FROM tabla_usuarios WHERE usuario=:usuario");
        $prepared->execute(array(":usuario" => $_POST["user"]));

        if(($result = $prepared->fetch(PDO::FETCH_ASSOC)) != null) {
            $pass = $result["password"];

            if(password_verify($_POST["password"], $pass)) {
                setcookie("usuario", $_POST["user"], time() + 3600);
                header("Location: ./login.php");
                exit();
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
    }else if($cookieOk == true){
        echo "
        <h1>Login</h1>
        <p>Ha iniciado sesión como ". $_COOKIE['usuario'] .", desea continuar?</p>
        <form action='#' method='POST'>
            <input type='submit' id='si' name='si' value='Si'>
            <input type='submit' id='no' name='no' value='No'>
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