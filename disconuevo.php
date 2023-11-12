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

    echo '<div id="botoncerrar"><a href="./disconuevo.php?cerrarSesion=true">Cerrar Sesión</a></div>';
    echo '<div id="cuerpo">';


    $tituloAlbum = "";
    $codAlbum = "";
    $titulo = "";
    $discografica = "";
    $formato = "";
    $fechaCompra = "";
    $fechaLanzamiento = "";
    $precio = "";
    $errorTitulo = "";
    $errorDiscografica = "";
    $errorformato = "";
    $errorFechaLanzamiento = "";
    $errorFechaCompra = "";
    $errorPrecio = "";
    $insertado = "";
    $flag = true;

    if(isset($_GET["cod"])){
        $codAlbum = $_GET["cod"];
    }

    if(isset($_GET["tit"])){
        $tituloAlbum = $_GET["tit"];
    }

    if(isset($_POST["titulo"]) && $_POST["titulo"] == ""){
        $errorTitulo = " *Este campo no puede estar vacío.";
        $flag = false;
    }

    if(isset($_POST["titulo"])){
        $titulo = $_POST["titulo"];
    }

    if(isset($_POST["discografica"]) && $_POST["discografica"] == ""){
        $errorDiscografica = " *ERROR";
        $flag = false;
    }

    if(isset($_POST["discografica"])){
        $discografica = $_POST["discografica"];
    }

    if(isset($_POST["formato"])){
        $formato = $_POST["formato"];
    }

    if(isset($_POST["fechaCompra"]) && $_POST["fechaCompra"] == ""){
        $errorFechaCompra = " *Este campo no puede estar vacío.";
        $flag = false;
    }

    if(isset($_POST["fechaCompra"])){
        $fechaCompra = $_POST["fechaCompra"];
    }

    if(isset($_POST["fechaLanzamiento"]) && $_POST["fechaLanzamiento"] == ""){
        $errorFechaLanzamiento = " *Este campo no puede estar vacío.";
        $flag = false;
    }

    if(isset($_POST["fechaLanzamiento"])){
        $fechaLanzamiento = $_POST["fechaLanzamiento"];
    }

    if(isset($_POST["precio"]) && $_POST["precio"] == ""){
        $errorPrecio = " *Este campo no puede estar vacío.";
        $flag = false;
    }

    if(isset($_POST["precio"])){
        $precio = $_POST["precio"];
    }

    if(isset($_GET["insertado"])){
        if($_GET["insertado"] == true){
            $insertado = 'Album guardado con éxito.';
        }else{
            $insertado = 'ERROR: El album no ha podido guardarse.';
        }
    }

    echo '<h1>Creación de nuevo album</h1>';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && $flag == true){
        $query = "SELECT codigo FROM album ORDER BY codigo asc";
        $preparedSelect = $disco->prepare($query);
        $preparedSelect->execute();
        $codigo;

        while(($cod = $preparedSelect->fetch(PDO::FETCH_NUM)) != null){
            $codigo = $cod[0];
        }

        $codigo++;

        $insert = "INSERT INTO album (codigo, titulo, discografica, formato, fechaLanzamiento, fechaCompra, precio) VALUES (:codigo, :titulo, :discografica, :formato, :fechaLanzamiento, :fechaCompra, :precio)";
        $preparedInsert = $disco->prepare($insert);
        $insertCorrecto = $preparedInsert->execute(array(":codigo" => $codigo, ":titulo" => $titulo,":discografica" => $discografica,":formato" => $formato, ":fechaLanzamiento"=> $fechaLanzamiento, ":fechaCompra"=> $fechaCompra, ":precio" => $precio));
        if($insertCorrecto){
            header("Location: ./disconuevo.php?insertado=true");
            exit();
        }else{
            header("Location: ./disconuevo.php?insertado=false");
            exit();
        }
    }else{
        echo $insertado."<br><br>";
        echo '<form action="#" method="POST" id="formulario">';
        echo '<label for="titulo">Título: </label>';
        echo '<input type="text" name="titulo" id="titulo" value="'.$titulo.'">'.$errorTitulo.'<br><br>';
        echo '<label for="discografica">Discográfica: </label>';
        echo '<input type="text" name="discografica" id="discografica" value="'.$discografica.'">'.$errorDiscografica.'<br><br>';
        echo '<label for="formato">Formato: </label>';
        echo '<select name="formato" id="formato" form="formulario">
                <option value="vinilo"';
                if($formato == "vinilo")echo' selected';
                echo '>Vinilo</option>
                <option value="cd"';
                if($formato == "cd")echo' selected';
                echo '>CD</option>
                <option value="dvd"';
                if($formato == "dvd")echo' selected';
                echo '>DVD</option>
                <option value="mp3"';
                if($formato == "mp3")echo' selected';
                echo'>MP3</option>
            </select><br><br>
            <label for="fechaLanzamiento">Fecha de Lanzamiento: </label>
            <input type="date" name="fechaLanzamiento" id="fechaLanzamiento" value="'.$fechaLanzamiento.'">'.$errorFechaLanzamiento.'<br><br>
            <label for="fechaCompra">Fecha de Compra: </label>
            <input type="date" name="fechaCompra" id="fechaCompra" value="'.$fechaCompra.'">'.$errorFechaCompra.'<br><br>
            <label for="precio">Precio: </label>
            <input type="number" name="precio" id="precio" step="0.01" value="'.$precio.'">'.$errorPrecio.'<br><br>';
        echo '<input type="submit">';
        echo '</form>';
    }

    echo "</div></body></html>";
?>


