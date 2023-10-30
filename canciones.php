<?php
    echo '<html>
        <link rel="stylesheet" href="./css/style.css">';
    $disco;
    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
        exit();
    }

    $titulo = "";
    $genero = "";
    $buscar = "";
    $errorTitulo = "";
    $flag = true;

    if(isset($_POST["titulo"]) && $_POST["titulo"] == ""){
        $errorTitulo = " *Este campo no puede estar vacío.";
        $flag = false;
    }

    if(isset($_POST["titulo"])){
        $titulo = $_POST["titulo"];
    }

    if(isset($_POST["buscar"])){
        $buscar = $_POST["buscar"];
    }

    if(isset($_POST["genero"])){
        $genero = $_POST["genero"];
    }

    echo '<h1>Búsqueda de canciones</h1>';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && $flag == true){
        echo '<form action="#" method="POST" id="formulario">';
        echo '<label for="titulo">Texto a buscar: </label>';
        echo '<input type="text" name="titulo" id="titulo" value="'.$titulo.'">'.$errorTitulo.'<br><br>';
        echo '<label for="">Buscar en: </label>';
        echo '<input type="radio" name="buscar" id="tituloCancion" value="tituloCancion" checked><label for="tituloCancion">Titulos de canción 
            <input type="radio" name="buscar" id="tituloAlbum" value="tituloAlbum"><label for="tituloAlbum">Nombres de álbum 
            <input type="radio" name="buscar" id="tituloAmbos" value="tituloAmbos"><label for=""tituloAmbos>Ambos campos<br><br>';
        echo '<label for="genero">Género musical: </label>';
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
            </select><br><br>';
        echo '<input type="submit" value="Buscar">';
        echo '</form>';

        echo '<h1>Listado de Canciones</h1><br><br>';
        echo '<table><tr>
            <th>Título</th>
            <th>Álbum</th>
            <th>Duración</th>
            <th>Género</th>
            </tr>';
        if($buscar == "tituloCancion"){
            $select = "SELECT * FROM cancion WHERE genero=:genero AND titulo LIKE '%".$titulo."%';";
            $buscar = $disco->prepare($select);
            $buscar->bindParam(":genero", $genero, PDO::PARAM_STR);
            $buscar->execute();
            while(($result = $buscar->fetch(PDO::FETCH_NUM)) != null){
                echo "<tr><td>".$result[0]."</td>";
                $select2 = "SELECT titulo FROM album WHERE codigo=:codigo;";
                $buscarAlbum = $disco->prepare($select2);
                $buscarAlbum->bindParam(":codigo", $result[1], PDO::PARAM_INT);
                $buscarAlbum->execute();
                $albumCodigo = $buscarAlbum->fetch(PDO::FETCH_NUM);
                echo "<td>".$albumCodigo[0]."</td>";
                echo "<td>".$result[3]."</td>
                <td>".$result[4]."</td>
                </tr>";
            }
        }else if($buscar == "tituloAlbum"){
            $select = "SELECT c.* FROM cancion c INNER JOIN album a ON c.album = a.codigo WHERE a.titulo LIKE '%".$titulo."%' AND c.genero = :genero;";
            $buscar = $disco->prepare($select);
            $buscar->bindParam(":genero", $genero, PDO::PARAM_STR);
            $buscar->execute();
            while(($result = $buscar->fetch(PDO::FETCH_NUM)) != null){
                echo "<tr><td>".$result[0]."</td>";
                $select2 = "SELECT titulo FROM album WHERE codigo=:codigo;";
                $buscarAlbum = $disco->prepare($select2);
                $buscarAlbum->bindParam(":codigo", $result[1], PDO::PARAM_INT);
                $buscarAlbum->execute();
                $albumCodigo = $buscarAlbum->fetch(PDO::FETCH_NUM);
                echo "<td>".$albumCodigo[0]."</td>";
                echo "<td>".$result[3]."</td>
                <td>".$result[4]."</td>
                </tr>";
            }
        }else if($buscar == "tituloAmbos"){
            $select = "SELECT c.* FROM cancion c INNER JOIN album a ON c.album = a.codigo WHERE a.titulo LIKE '%".$titulo."%' AND c.titulo LIKE '%".$titulo."%' AND c.genero = :genero;";
            $buscar = $disco->prepare($select);
            $buscar->bindParam(":genero", $genero, PDO::PARAM_STR);
            $buscar->execute();
            while(($result = $buscar->fetch(PDO::FETCH_NUM)) != null){
                echo "<tr><td>".$result[0]."</td>";
                $select2 = "SELECT titulo FROM album WHERE codigo=:codigo;";
                $buscarAlbum = $disco->prepare($select2);
                $buscarAlbum->bindParam(":codigo", $result[1], PDO::PARAM_INT);
                $buscarAlbum->execute();
                $albumCodigo = $buscarAlbum->fetch(PDO::FETCH_NUM);
                echo "<td>".$albumCodigo[0]."</td>";
                echo "<td>".$result[3]."</td>
                <td>".$result[4]."</td>
                </tr>";
            }
        }

        echo "</table>";
    }else{
        echo '<form action="#" method="POST" id="formulario">';
        echo '<label for="titulo">Texto a buscar: </label>';
        echo '<input type="text" name="titulo" id="titulo" value="'.$titulo.'">'.$errorTitulo.'<br><br>';
        echo '<label for="">Buscar en: </label>';
        echo '<input type="radio" name="buscar" id="tituloCancion" value="tituloCancion" checked><label for="tituloCancion">Titulos de canción 
            <input type="radio" name="buscar" id="tituloAlbum" value="tituloAlbum"><label for="tituloAlbum">Nombres de álbum 
            <input type="radio" name="buscar" id="tituloAmbos" value="tituloAmbos"><label for=""tituloAmbos>Ambos campos<br><br>';
        echo '<label for="genero">Género musical: </label>';
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
            </select><br><br>';
        echo '<input type="submit" value="Buscar">';
        echo '</form>';
    }

    echo "</html>";
?>