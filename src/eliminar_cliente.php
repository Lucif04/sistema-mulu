<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "DELETE FROM cliente WHERE id_cliente = $id");
    mysqli_close($conexion);
    header("Location: clientes.php");
}
