
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

    echo '<div id="botoncerrar"><a href="./index.php?cerrarSesion=true">Cerrar Sesión</a></div>';
    echo '<div id="cuerpo">';
    
    $actualizado = "";
    $borrardisco = "";

    if(isset($_GET['actualizado'])){
        $actualizado = 'Se ha actualizado el stock correctamente.';
    }

    if(isset($_GET['borrardisco']) && $_GET['borrardisco'] == true){
        $borrardisco = "El album se ha borrado correctamente";
    }

    $query = "SELECT * FROM album";
    $resultado = $disco->prepare($query);
    $resultado->execute();
    echo '<h1>Listado de discos</h1><br>
        '.$actualizado.'<br>
        '.$borrardisco.'<br><br>';
    echo '<table><tr>
            <th>Código</th>
            <th>Titulo</th>
            <th>Discografía</th>
            <th>Formato</th>
            <th>Fecha de Lanzamiento</th>
            <th>Fecha de Compra</th>
            <th>Precio</th>
            </tr>';
    while(($albumes = $resultado->fetch(PDO::FETCH_NUM)) != null){
        echo "<tr><td>".$albumes[0]."</td>
            <td><a href='disco.php?cod=".$albumes[0]."&tit=".$albumes[1]."'>".$albumes[1]."</td>
            <td>".$albumes[2]."</td>
            <td>".$albumes[3]."</td>
            <td>".$albumes[4]."</td>
            <td>".$albumes[5]."</td>
            <td>".$albumes[6]."€</td>
            </tr>";
    }

    echo "</table><br><br><a href='./disconuevo.php'>Añadir nuevo disco</a>
         <a href='./canciones.php'>Buscar canciones</a>
        </div><body></html>";
?>



