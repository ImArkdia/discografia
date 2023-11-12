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

    echo '<div id="botoncerrar"><a href="./cancionnueva.php?cerrarSesion=true">Cerrar Sesión</a></div>';
    echo '<div id="cuerpo">';


    $tituloAlbum = "";
    $codAlbum = "";
    $titulo = "";
    $posicion = "";
    $duracion = "";
    $genero = "";
    $errorTitulo = "";
    $errorPosicion = "";
    $errorDuracion = "";
    $errorGenero = "";
    $errorExiste = "";
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

    if(isset($_POST["duracion"]) && $_POST["duracion"] == ""){
        $errorDuracion = " *ERROR";
        $flag = false;
    }

    if(isset($_POST["duracion"])){
        $duracion = $_POST["duracion"];
    }

    if(isset($_POST["genero"])){
        $genero = $_POST["genero"];
    }

    if(isset($_GET["insertado"])){
        if($_GET["insertado"] == true){
            $insertado = 'Canción guardada con éxito.';
        }else{
            $insertado = 'ERROR: La canción no ha podido guardarse.';
        }
    }

    if(isset($_GET['existe']) && $_GET['existe'] == true){
        $errorExiste = "ERROR: El título de la canción ya existe";
    }

    echo '<h1>Insertando canciones en el album: '. $tituloAlbum.'</h1>';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && $flag == true){
        $query = "SELECT titulo FROM cancion WHERE album=:album AND titulo=:titulo";
        $preparedSelectTitulo = $disco->prepare($query);
        $preparedSelectTitulo->execute(array(":titulo" => $titulo, ":album" => $codAlbum));

        if(($existeTitulo = $preparedSelectTitulo->fetch(PDO::FETCH_NUM)) == null){
            $query = "SELECT posicion FROM cancion WHERE album=:album ORDER BY posicion asc";
            $preparedSelect = $disco->prepare($query);
            $preparedSelect->bindParam(":album", $codAlbum);
            $preparedSelect->execute();
            $position;
            while(($pos = $preparedSelect->fetch(PDO::FETCH_NUM)) != null){
                $position = $pos[0];
            }

            $position++;

            $insert = "INSERT INTO cancion (titulo, album, posicion, duracion, genero) VALUES (:titulo, :album, :posicion, :duracion, :genero)";
            $preparedInsert = $disco->prepare($insert);
            $insertCorrecto = $preparedInsert->execute(array(":titulo" => $titulo,":album" => $codAlbum,":posicion" => $position, ":duracion"=> $duracion, ":genero"=> $genero));
            if($insertCorrecto){
                header("Location: ./cancionnueva.php?insertado=true&cod=".$codAlbum."&tit=".$tituloAlbum);
                exit();
            }else{
                header("Location: ./cancionnueva.php?insertado=false&cod=".$codAlbum."&tit=".$tituloAlbum);
                exit();
            }
        }else{
            header("Location: ./cancionnueva.php?existe=true&cod=".$codAlbum."&tit=".$tituloAlbum);
            exit();
        }
    }else{
        echo $insertado."<br>".$errorExiste."<br><br>";
        echo '<form action="#" method="POST" id="formulario">';
        echo '<label for="titulo">Título: </label>';
        echo '<input type="text" name="titulo" id="titulo" value="'.$titulo.'">'.$errorTitulo.'<br><br>';
        echo '<label for="duracion">Duración: </label>';
        echo '<input type="time" name="duracion" id="duracion" step="1" value="'.$duracion.'">'.$errorDuracion.'<br><br>';
        echo '<label for="genero">Género: </label>';
        echo '<select name="genero" id="genero" form="formulario">
                <option value="Clásica"';
                if($genero == "Clásica")echo' selected';
                echo '>Clásica</option>
                <option value="BSO"';
                if($genero == "BSO")echo' selected';
                echo '>BSO</option>
                <option value="Blues"';
                if($genero == "Blues")echo' selected';
                echo '>Blues</option>
                <option value="Electrónica"';
                if($genero == "Electrónica")echo' selected';
                echo'>Electrónica</option>
                <option value="Jazz"';
                if($genero == "Jazz")echo' selected';
                echo '>Jazz</option>
                <option value="Metal"';
                if($genero == "Metal")echo' selected';
                echo '>Metal</option>
                <option value="Pop"';
                if($genero == "Pop")echo' selected';
                echo '>Pop</option>
                <option value="Rock"';
                if($genero == "Rock")echo' selected';
                echo'>Rock</option>
            </select>';
        echo '<input type="submit">';
        echo '</form>';
    }

    echo "</div></body></html>";
?>


