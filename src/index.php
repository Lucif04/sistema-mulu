<?php
require "../conexion.php";
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$total['usuarios'] = mysqli_num_rows($usuarios);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$total['clientes'] = mysqli_num_rows($clientes);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$total['productos'] = mysqli_num_rows($productos);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas WHERE fecha > CURDATE()");
$total['ventas'] = mysqli_num_rows($ventas);
session_start();
include_once "includes/header.php";
?>
<!-- Content Row -->
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-user fa-2x"></i>
                </div>
                <a href="usuarios" class="card-category text-warning font-weight-bold">
                    Usuarios
                </a>
                <h3 class="card-title"><?php echo $total['usuarios']; ?></h3>
            </div>
            <div class="card-footer bg-warning text-white">
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <a href="clientes" class="card-category text-success font-weight-bold">
                    Clientes
                </a>
                <h3 class="card-title"><?php echo $total['clientes']; ?></h3>
            </div>
            <div class="card-footer bg-secondary text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="fab fa-product-hunt fa-2x"></i>
                </div>
                <a href="productos" class="card-category text-danger font-weight-bold">
                    Productos
                </a>
                <h3 class="card-title"><?php echo $total['productos']; ?></h3>
            </div>
            <div class="card-footer bg-primary">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="fas fa-cash-register fa-2x"></i>
                </div>
                <a href="ventas" class="card-category text-info font-weight-bold">
                    Ventas
                </a>
                <h3 class="card-title"><?php echo $total['ventas']; ?></h3>
            </div>
            <div class="card-footer bg-danger text-white">
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos con stock mínimo</h3>
            </div>
            <div class="card-body">
                <canvas id="stockMinimo"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Productos más vendidos</h3>
            </div>
            <div class="card-body">
                <canvas id="ProductosVendidos"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h3 class="title-2 m-b-40">Ventas por mes</h3>
            </div>
            <div class="card-body">
                <canvas id="VentasPorMes"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <?php
                // Creamos un array de meses, sino de lo contrario lo imprime en ingles
                $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                ?>
                <h3 class="title-2 m-b-40">Ventas por día (<?php echo $meses[date('n')-1]; ?>)</h3>
            </div>
            <div class="card-body">
                <!-- Creamos una tabla para la ventas por día de este mes -->
                <table class="table table-light" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>Día</th>
                            <th>Cantidad ventas</th>
                            <th>Gasto</th>
                            <th>Ganancia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Traemos las ventas por día de este mes, sacando cantidad por día, ganancia (precioVenta - precioCompra), gastos (precioCompra * cantidad) y cantidad de productos vendidos
                        $query = mysqli_query($conexion, "SELECT DAY(ventas.fecha) as dia, COUNT(ventas.id) as cantidad_ventas, SUM(producto.precioCompra * detalle_venta.cantidad) as gasto, SUM((producto.precioVenta * detalle_venta.cantidad) - (producto.precioCompra * detalle_venta.cantidad) ) as ganancia 
                        FROM ventas
                        INNER JOIN detalle_venta ON ventas.id = detalle_venta.id_venta
                        INNER JOIN producto ON detalle_venta.id_producto = producto.codproducto
                        WHERE MONTH(ventas.fecha) = MONTH(CURDATE())
                        GROUP BY DAY(ventas.fecha)");
                        while ($row = mysqli_fetch_assoc($query)) {
                            $total_gastos += $row['gasto'];
                            $total_ganancias += $row['ganancia'];
                        ?>
                            <tr>
                                <td><?php echo $row['dia']; ?></td>
                                <td><?php echo $row['cantidad_ventas']; ?></td>
                                <td><?php echo '$' . number_format($row['gasto'], 0, '', '.'); ?></td>
                                <!-- Agregamos un number format -->
                                <td><?php echo '$' . number_format($row['ganancia'], 0, '', '.'); ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        <!-- Creamos una fila con el total gastos y otro total de ganancia para DataTable -->
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="font-weight-bold"><?php echo 'Total: $' .  number_format($total_gastos, 0, '', '.') ?></td>
                            <td class="font-weight-bold"><?php echo 'Total: $' .  number_format($total_ganancias, 0, '', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>