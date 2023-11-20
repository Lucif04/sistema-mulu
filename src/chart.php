<?php
include("../conexion.php");
if ($_POST['action'] == 'sales') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT descripcion, stock FROM producto WHERE stock <= 10 ORDER BY stock ASC LIMIT 10");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}
if ($_POST['action'] == 'polarChart') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT p.codproducto, p.descripcion, d.id_producto, d.cantidad, SUM(d.cantidad) as total FROM producto p INNER JOIN detalle_venta d WHERE p.codproducto = d.id_producto group by d.id_producto ORDER BY d.cantidad DESC LIMIT 5");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}
if ($_POST['action'] == 'ventasPorMes') {
    $arreglo = array();
    // Contamos las ventas de este año agrupadas por mes, si un mes no tiene ventas, le ponemos 0
    $query = mysqli_query($conexion, "SELECT MONTH(fecha) as mes, COUNT(*) as cantidad_ventas FROM ventas WHERE YEAR(fecha) = YEAR(CURDATE()) GROUP BY MONTH(fecha)");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}
?>