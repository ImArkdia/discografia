<?php
    echo '<html>
        <link rel="stylesheet" href="./css/style.css">';
    @$dwes = new mysqli('localhost', 'dwes', 'dwes', 'dwes');
    if ($dwes->connect_errno != null) {
        echo 'Error conectando a la base de datos: ';
        echo $dwes->connect_error;
        exit();
    }
    $dwes->autocommit(false);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $cod = $_GET['cod'];
        unset($_POST['cod']);
        $keys = array_keys($_POST);
        $values = array_values($_POST);
        
        for ($i = 0; $i < count($keys); $i++) {
            $unidades = $values[$i];
            $tiendas = $keys[$i];
        
            $preparedDwes = $dwes->prepare('UPDATE stock SET unidades=? WHERE producto=? AND tienda=?');
            $preparedDwes->bind_param('isi', $unidades, $cod, $tiendas);
            $preparedDwes->execute();
            $preparedDwes->close();
        }
        $dwes->commit();
        $dwes->close();
        header("Location: ./index.php?actualizado=true");
        exit();
    }else{
        $query = "SELECT * FROM stock WHERE producto='".$_GET['cod']."'";
        $stock = $dwes->query($query, MYSQLI_STORE_RESULT);
        if($stock->num_rows < 0){
            echo 'Ninguna tienda tiene ese producto.';
        }else{
            
            echo "<h1>Stock del producto '".$_GET['cod']."' en las tiendas:</h1>
                <form method='POST'>";
            while($infoStock = $stock->fetch_row()){
                $query2 = "SELECT * FROM tienda WHERE cod=".$infoStock[1];
                $tienda = $dwes->query($query2, MYSQLI_STORE_RESULT);
                if($tienda->num_rows > 0){
                    $infoTienda = $tienda->fetch_row();
                    echo "Tienda ".strtoupper($infoTienda[1]).": 
                        <input type='text' name='".$infoStock[1]."' value='".$infoStock[2]."' style='width: 50px;'> unidades.<br><br>";
                }
            }
            echo "<input type='submit' value='Actualizar'>
                <input type='hidden' name='cod'".$_GET['cod']."'>
                </form>";
        }

        $dwes->close();
    }
    echo "</html>";
?>


