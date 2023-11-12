<?php
    $disco;
    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
    }

    $user = "";
    $userError = "";
    $passError = "";
    $ok = true;
    $existingUser = "";

    if(isset($_GET["existingUser"]) && $_GET["existingUser"] == true){
        $existingUser = "El usuario introducido ya existe.";
    }

    if(isset($_POST["user"]) && $_POST["user"] != ""){
        $user = $_POST["user"];
    }

    if(isset($_POST["user"]) && $_POST["user"] == ""){
        $userError = "*Este campo no puede estar vacío";
        $ok = false;
    }

    if(isset($_POST["pass"]) && $_POST["pass"] == ""){
        $passError = "*Este campo no puede estar vacío";
        $ok = false;
    }

    
    if(isset($_POST["enviar"]) && $ok == true){
        $prepared = $disco->prepare("SELECT * FROM tabla_usuarios WHERE usuario=:usuario");
        $prepared->execute(array(":usuario" => $_POST["user"]));

        if(($result = $prepared->fetch(PDO::FETCH_ASSOC)) != null){
            header("Location: ./registro.php?existingUser=true");
            exit();
        }else{
            $cryptedPass = password_hash($pass, PASSWORD_DEFAULT);
        
            $prepared = $disco->prepare("INSERT INTO tabla_usuarios (usuario, password) VALUES(:usuario, :password)");
            $prepared->execute(array(":usuario" => $user, ":password" => $cryptedPass));
            header("Location: ./login.php?registrado=true");
            exit();
        }

    }else{
        echo "
        <h1>Registro</h1>
        <form action='#' method='POST'>
            <label for='user'>Usuario: </label><br>
            <input type='text' name='user' id='user'>". $userError ."<br><br>
            <label for='pass'>Contraseña:</label><br>
            <input type='password' name='pass' id='pass'>". $passError ."<br><br>
            <input type='submit' name='enviar' id='enviar'><br>
            ". $existingUser ."<br>
        </form>
        ";
    }
?>