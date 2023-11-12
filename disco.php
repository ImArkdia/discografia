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

    echo '<div id="botoncerrar"><a href="./disco.php?cerrarSesion=true">Cerrar Sesión</a></div>';
    echo '<div id="cuerpo">';


    $query = "SELECT * FROM cancion WHERE album='".$_GET['cod']."' ORDER BY posicion ASC;";
    $album = $disco->prepare($query);
    $album->execute();

    echo "<h1>Canciones del disco: '".$_GET['tit']."'</h1><br>";
    echo '<table><tr>
            <th>Título</th>
            <th>Posición</th>
            <th>Duración</th>
            <th>Género</th>
            </tr>';
    while(($canciones = $album->fetch(PDO::FETCH_NUM)) != null){
        echo "<tr><td>".$canciones[0]."</td>
            <td>".$canciones[2]."</td>
            <td>".$canciones[3]."</td>
            <td>".$canciones[4]."</td>
            </tr>";
    }

    echo "</table><br><br>
        <a href='cancionnueva.php?cod=".$_GET['cod']."&tit=".$_GET['tit']."'>Añadir canción</a>
        <a href='borrardisco.php?cod=".$_GET['cod']."&tit=".$_GET['tit']."'>Borrar album</a>";
    echo "</div></body></html>";
?>


