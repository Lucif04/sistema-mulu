<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM pedidos WHERE idPedido = $id");
    mysqli_close($conexion);
    header("Location: pedidos");
}