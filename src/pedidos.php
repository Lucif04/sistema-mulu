<?php

session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];


if (!empty($_POST)) {
    $alert = "";
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $pedido = $_POST['pedido'];
    $fecha = $_POST['fecha'];
    
    
    if (empty($nombre) || empty($telefono) || empty($pedido)) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        if (!empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM pedidos WHERE id_pedido = '$id'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $query_update = mysqli_query($conexion, "UPDATE pedidos SET nombre = '$nombre', telefono= '$telefono', pedido = '$pedido', fecha = '$fecha' WHERE id_pedido = $id");
                if ($query_update) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Pedido Modificado
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
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO pedidos(id_pedido, nombre,telefono,pedido,fecha) values ($id,'$nombre', '$telefono', '$pedido', '$fecha')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                Pedido registrado
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                    Error al registrar el pedido
                  </div>';
                }
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
                    <?php echo isset($alert) ? $alert :''; ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre" class=" text-dark font-weight-bold">Nombre</label>
                                <input type="text" placeholder="Ingrese nombre del producto" name="nombre" id="nombre" class="form-control">
                                <input type="hiden" name="id" id="id" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefono" class=" text-dark font-weight-bold">Telefono</label>
                                <input type="tel" placeholder="Ingrese precio de compra" class="form-control" name="telefono" id="telefono">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fecha" class=" text-dark font-weight-bold">fecha</label>
                                <input type="date" class="form-control" name="fecha" id="fecha"  value="<?php echo date("Y-m-d");?>">
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="preciov" class=" text-dark font-weight-bold">Pedido</label>
                                <input type="text" placeholder="Ingrese precio de venta" class="form-control" name="pedido" id="pedido">
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
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Pedido</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";

                        $query = mysqli_query($conexion, "SELECT * FROM pedidos");
                        $result = mysqli_num_rows($query);
                        
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                                $vendido = $data['vendido'];
                                $fecha = $data['fecha'];
                                $fecha_timestamp = strtotime($fecha);
                                $nueva_fecha = date("d-m-Y", $fecha_timestamp);
                                if( $vendido == 0 ){ 
                                ?>
                                    <tr class="text-center">
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['pedido']; ?></td>
                                        <td><?php echo $nueva_fecha; ?></td>
                                        <td>
                                            <a href="pedidos" onclick="editarPedido(<?php echo $data['id_pedido']; ?>)" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="pedidos" onclick="tildarPedido(<?php echo $data['id_pedido']; ?>)" class="btn btn-success btn-sm"><i class="fas fa-check"></i></a>
                                            <form action="eliminar_pedido.php?id=<?php echo $data['id_pedido']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }else if ($vendido == 1) {
                                ?>
                                    <tr class="text-center table-success">
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['pedido']; ?></td>
                                        <td><?php echo $nueva_fecha; ?></td>
                                        <td>
                                            <a href="pedidos" onclick="editarPedido(<?php echo $data['id_pedido']; ?>)" class="btn btn-primary btn-sm"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar_pedido.php?id=<?php echo $data['id_pedido']; ?>" method="post" class="confirmar d-inline">
                                                <button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                    <?php       }
                         }
                        }?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>