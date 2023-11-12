<?php

    session_start();

    $iniciadoSesion = false;

    if(isset($_SESSION["token"]) && isset($_SESSION["user"])){
        $prepared = $disco->prepare("SELECT * FROM tabla_usuarios WHERE usuario=:usuario AND password=:password");
        $prepared->execute(array(":usuario" => $_SESSION["user"], ":password" => $_SESSION["token"]));

        if(($result = $prepared->fetch(PDO::FETCH_ASSOC)) != null) {
            $iniciadoSesion = true;
        }else{
            header("Location: ./login.php");
            exit();
        }
    }

    echo '<!DOCTYPE HTML>
        <html>
        <link rel="stylesheet" href="./css/style.css">
    ';

    try {
        $disco = new PDO('mysql:host=localhost;dbname=discografia', 'disco', 'disco');
    } catch (PDOException $e) {
        echo 'Falló la conexión: '. $e->getMessage();
        exit();
    }

    try {
        $ok = true;
        $disco->beginTransaction();
        $select = $disco->prepare('SELECT * FROM cancion WHERE codigo='.$_GET['cod'].';');
        if($select->rowCount() > 0) {
            $borrarCanciones = "DELETE FROM cancion WHERE album=".$_GET['cod'].";";
            if($disco->exec($borrarCanciones) == 0) {
                $ok = false;
            }
        }
        
        $borrarAlbum = "DELETE FROM album WHERE codigo=".$_GET['cod'].";";

        if($disco->exec($borrarAlbum) == 0) {
            $ok = false;
        }

        if($ok){
            $disco->commit();
            header("Location: ./index.php?borrardisco=true");
            exit();
        }else{
            $disco->rollBack();
            header("Location: ./disco.php?cod=".$_GET['cod']."&tit=".$_GET['tit']."&borrardisco=false");
            exit();
        }
    } catch (PDOException $th) {
        echo 'ERROR: '. $th->getMessage();
    }catch (Exception $the) {
        echo 'ERROR: '. $the->getMessage();
    }

    
?>