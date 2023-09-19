<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];

if (!empty($_POST)) {
    $alert = "";
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $precioc = $_POST['precioc'];
    $preciov = $_POST['preciov'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];
    if (empty($codigo) || empty($producto)  || empty($precioc) || $precioc <  0 || empty($cantidad) || $cantidad <  0) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        El codigo ya existe
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,precioCompra,precioVenta,stock,fecha) values ('$codigo', '$producto', '$precioc', '$preciov', '$cantidad', '$fecha')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto registrado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el producto
                  </div>';
                }
            }
        } else {
            $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', precioCompra= '$precioc', precioVenta = '$preciov', stock = '$cantidad', fecha = '$fecha' WHERE codproducto = $id");
            if ($query_update) {
                $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Producto Modificado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            } else {
                $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Error al modificar
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            }
        }
    }
}
include_once "includes/header.php";
?>

<div class="card shadow-lg">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" autocomplete="off" id="formulario">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codigo" class=" text-dark font-weight-bold"><i class="fas fa-barcode"></i> Cod. de producto</label>
                                <input type="text" placeholder="Ingrese código de barras" name="codigo" id="codigo" class="form-control">
                                <input type="hidden" id="id" name="id">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="producto" class=" text-dark font-weight-bold">Producto</label>
                                <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="precioc" class=" text-dark font-weight-bold">Precio de compra</label>
                                <input type="text" placeholder="Ingrese precio de compra" class="form-control" name="precioc" id="precioc">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="preciov" class=" text-dark font-weight-bold">Precio de venta</label>
                                <input type="text" placeholder="Ingrese precio de venta" class="form-control" name="preciov" id="preciov">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cantidad" class=" text-dark font-weight-bold">Cantidad</label>
                                <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha" class=" text-dark font-weight-bold">Fecha de compra</label>
                                <input type="date" placeholder="Ingrese fecha de compra" class="form-control" name="fecha" id="fecha">
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-md-center">
                            <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                            <input type="button" value="Nuevo" onclick="limpiar()" class="btn btn-success" id="btnNuevo">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Fecha Compra</th>
                        <th>Ganancia</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../conexion.php";

                    $query = mysqli_query($conexion, "SELECT * FROM producto");
                    $result = mysqli_num_rows($query);

                    if ($result > 0) {
                        while ($data = mysqli_fetch_assoc($query)) {
                            $preciocom = $data['precioCompra'];
                            $precioven = $data['precioVenta'];
                    ?>
                            <tr>
                                <td><?php echo $data['codigo']; ?></td>
                                <td><?php echo $data['descripcion']; ?></td>
                                <td><?php echo $data['precioCompra']; ?></td>
                                <td><?php echo $data['precioVenta']; ?></td>
                                <td><?php echo $data['stock']; ?></td>
                                <td><?php echo $data['fecha']; ?></td>
                                <td><?php echo number_format($precioven - $preciocom, 2) ?></td>
                                <td>
                                    <a href="productos" onclick="editarProducto(<?php echo $data['codproducto']; ?>)" class="btn btn-primary"><i class='fas fa-edit'></i></a>
                                    <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                        <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                    </form>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
</div>
<?php include_once "includes/footer.php"; ?>