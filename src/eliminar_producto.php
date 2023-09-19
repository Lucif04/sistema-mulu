<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM producto WHERE codproducto = $id");
    mysqli_close($conexion);
    header("Location: productos.php");
}
