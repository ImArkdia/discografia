
<?php
    echo '<html>
        <link rel="stylesheet" href="./css/style.css">
    ';

    $disco;
    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
    }
    
    $actualizado = "";

    if(isset($_GET['actualizado'])){
        $actualizado = 'Se ha actualizado el stock correctamente.';
    }

    $query = "SELECT * FROM album";
    $resultado = $disco->prepare($query);
    $resultado->execute();
    echo '<h1>Listado de discos</h1><br><br>
        '.$actualizado.'<br><br>';
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
            <td>".$albumes[2]."€</td>
            <td>".$albumes[3]."</td>
            <td>".$albumes[4]."</td>
            <td>".$albumes[5]."</td>
            <td>".$albumes[6]."€</td>
            </tr>";
    }

    echo "</html>";
?>




