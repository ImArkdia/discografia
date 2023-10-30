<?php
    echo '<html>
        <link rel="stylesheet" href="./css/style.css">';
    $disco;
    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
    }

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
    echo "</html>";
?>


