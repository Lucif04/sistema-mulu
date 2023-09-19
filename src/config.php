<?php
session_start();
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];

$query = mysqli_query($conexion, "SELECT * FROM configuracion");
$data = mysqli_fetch_assoc($query);
if ($_POST) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['email']) || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Todo los campos son obligatorios
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    } else {
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $id = $_POST['id'];
        $imagen_actual = $_POST['imagen_actual'];
        $img = '';
        //Obtenemos el archivo
        $file = $_FILES["foto"];
        if (isset($file) && !empty($file["name"])) {
            if(isset($file)){
                //Obtenemos le extencion del archivo .jpg / .png etc
                $tipo = $file["type"];
                //extencion de la imagen
                $ext_img = explode("/" , $tipo);
                //Obtenemos el nombre del archivo
                $logo = "logoemp." . $ext_img[1];
                //el archivo se guarda provisoriamente en esta carpeta siempre
                $rut_provisoria = $file["tmp_name"];
                //tamaño de la foto
                $size = $file["size"];
                //carpeta donde guardo la foto
                if ($tipo != 'image/jpg' && $tipo != 'image/JPG' && $tipo != 'image/jpeg' && $tipo != 'image/jpg' && $tipo != 'image/png'){
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Error, el arhivo no es una imagen.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                }else if($size > 3*1024*1024){
                    $alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Erro, el tamaño maximo permitido es de 3MB.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                } else {
                    $src = "../upload/" . $logo;
                    //movemos la imagen a la carpeta (src)
                    move_uploaded_file($rut_provisoria, $src);
                    $img = $logo;
                }
            }    

        }else {
            $img = $imagen_actual;
        }
        
        $update = mysqli_query($conexion, "UPDATE configuracion SET nombre = '$nombre', telefono = '$telefono', email = '$email', direccion = '$direccion', img ='$img' WHERE id = $id");
        if ($update) {
            $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Datos Actualizado
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }
    }
}
include_once "includes/header.php"
?>

<head>
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Datos de la Empresa</h4>
            </div>
            <div class="card-body">
                <?php echo isset($alert) ? $alert : ''; ?>
                <form action="" method="post" class="p-3" enctype="multipart/form-data" >
                    <div class="mb-3">
                        <?php
                            if($data['img'] == ''){
                                ?>
                                    <label>&#8659; CARGA TU LOGO &#8659;</label>
                                    <input class="form-control" type="file" id="foto" name="foto">
                                <?php
                            }else{
                                ?>
                                    <div class = "d-flex justify-content-center">
                                        <div class= "d-flex justify-content-center contenedor-img">
                                            <img src="../upload/<?php echo $data['img']; ?>" alt="logo" class="img-fluid img-thumbnail">
                                        </div>
                                    </div>
                                    <br>
                                    <label>&#8659; Selecciones un nuevo logo &#8659;</label>
                                    <div class = "d-flex justify-content-center">
                                        <input type="hidden" name="imagen_actual" id="imagen_actual" value="<?php echo $data['img']; ?>">
                                        <input class="form-control" type="file" id="foto" name="foto" value="../upload/<?php echo $data['img']; ?>">
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                        <input type="text" name="nombre" class="form-control" value="<?php echo $data['nombre']; ?>" id="txtNombre" placeholder="Nombre de la Empresa" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Teléfono:</label>
                        <input type="text" name="telefono" class="form-control" value="<?php echo $data['telefono']; ?>" id="txtTelEmpresa" placeholder="teléfono de la Empresa" required>
                    </div>
                    <div class="form-group">
                        <label>Correo Electrónico:</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" id="txtEmailEmpresa" placeholder="Correo de la Empresa" required>
                    </div>
                    <div class="form-group">
                        <label>Dirección:</label>
                        <input type="text" name="direccion" class="form-control" value="<?php echo $data['direccion']; ?>" id="txtDirEmpresa" placeholder="Dirreción de la Empresa" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Modificar Datos</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>