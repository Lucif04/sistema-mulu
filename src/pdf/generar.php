<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 0);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 12);
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE id_cliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(30, 10, utf8_decode($datos['nombre']), 0, 1, 'C');
//guardamos la url para sacar su extension
$url = $datos['img'];
$url_img = "../" . $url;

$ext_img = explode("." , $url);

$pdf->image(($url_img),60, 10, 15, 15, $ext_img[1]);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 5, $datos['telefono'], 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 5, utf8_decode($datos['email']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(15, 5, "Direccion: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(15, 5, utf8_decode($datos['direccion']), 0, 0, 'L');
$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Datos del cliente", 1, 1, 'C', 1);

$pdf->Ln(2);

$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(20, 5, utf8_decode('Nombre'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Teléfono'), 0, 0, 'L');
$pdf->Cell(30, 5, utf8_decode('Correo'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 5, utf8_decode($datosC['nombre']), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($datosC['telefono']), 0, 0, 'L');
$pdf->Cell(30, 5, utf8_decode($datosC['correo']), 0, 1, 'L');

$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(20, 5, utf8_decode('Provincia'), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode('Localidad'), 0, 0, 'L');
$pdf->Cell(30, 5, utf8_decode('Direccion'), 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 5, utf8_decode($datosC['provincia']), 0, 0, 'L');
$pdf->Cell(20, 5, utf8_decode($datosC['localidad']), 0, 0, 'L');
$pdf->Cell(30, 5, utf8_decode($datosC['direccion']), 0, 0, 'L');


$pdf->Ln(7);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(70, 5, "Detalle de Producto", 1, 1, 'C', 1);

$pdf->Ln(2);

$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(30, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(10, 5, 'Cant.', 0, 0, 'C');
$pdf->Cell(15, 5, 'Precio', 0, 0, 'C');
$pdf->Cell(15, 5, 'Sub Total.', 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$total = 0.00;
$desc = 0.00;
while ($row = mysqli_fetch_assoc($ventas)) {
    $pdf->Cell(30, 5, $row['descripcion'], 0, 0, 'L');
    $pdf->Cell(10, 5, $row['cantidad'], 0, 0, 'C');
    $pdf->Cell(15, 5, '$ '.number_format($row['precio'], 0, '', '.'), 0, 0, 'C');
    $sub_total = $row['total'];
    $total = $total + $sub_total;
    $desc = $desc + $row['descuento'];
    $pdf->Cell(15, 5, '$ '.number_format($sub_total, 0, '', '.'), 0, 1, 'C');
}
$pdf->Ln();
/*$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(65, 5, 'Descuento Total', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(65, 5, number_format($desc, 2, '.', ','), 0, 1, 'R');*/
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(65, 5, 'Total Pagar', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(65, 5, '$ '.number_format($total, 0, '', '.'), 0, 1, 'R');

$pdf->Output("ventas.pdf", "I");

?>