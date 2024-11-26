<?php
require("../fpdf/fpdf.php");
// Configurar zona horaria
date_default_timezone_set("America/Lima");
class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo
        $this->Image('../multimedia/imagenes/logo2.png', 10, 10, 58);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 12);
        // Celda invisible para no tapar la imagen + Celda de fecha
        $this->Cell(58);
        $this->Cell(130, 10, "Fecha :  " . date("d/m/Y"), 1, 1, 'R');
        // Celda invisible + Celda de hora
        $this->Cell(58);
        $this->Cell(130, 10, "Hora :  " . date("h:i:s a"), 1, 0, 'R');
        // Salto de línea
        $this->Ln(15);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode("Página ") . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function WordWrap(&$text, $maxwidth)
{
    $text = trim($text);
    if ($text==='')
        return 0;
    $space = $this->GetStringWidth(' ');
    $lines = explode("\n", $text);
    $text = '';
    $count = 0;

    foreach ($lines as $line)
    {
        $words = preg_split('/ +/', $line);
        $width = 0;

        foreach ($words as $word)
        {
            $wordwidth = $this->GetStringWidth($word);
            if ($wordwidth > $maxwidth)
            {
                // Word is too long, we cut it
                for($i=0; $i<strlen($word); $i++)
                {
                    $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                    if($width + $wordwidth <= $maxwidth)
                    {
                        $width += $wordwidth;
                        $text .= substr($word, $i, 1);
                    }
                    else
                    {
                        $width = $wordwidth;
                        $text = rtrim($text)."\n".substr($word, $i, 1);
                        $count++;
                    }
                }
            }
            elseif($width + $wordwidth <= $maxwidth)
            {
                $width += $wordwidth + $space;
                $text .= $word.' ';
            }
            else
            {
                $width = $wordwidth + $space;
                $text = rtrim($text)."\n".$word.' ';
                $count++;
            }
        }
        $text = rtrim($text)."\n";
        $count++;
    }
    $text = rtrim($text);
    return $count;
}
}

// Acceder a la tabla de platos en la base de datos
include("../cls_conectar/cls_Conectar.php");
$cn = (new Conectar())->getConectar();
$sql = "SELECT * FROM tb_plato p 
        INNER JOIN tb_categoria cat ON cat.id_categoria = p.id_categoria
        ORDER BY cat.id_categoria;";
$rs = mysqli_query($cn, $sql);

// Creación del objeto de la clase heredada PDF
$pdf = new PDF();
$pdf->AliasNbPages();               // Alias para el número de páginas
$pdf->AddPage();                    // Nueva página

// Encabezado
$pdf->SetFont("Arial", "B", 15);              // Arial Bold 15
$pdf->SetFillColor(215, 189, 226);            // Color de relleno
$pdf->Cell(80);
$pdf->Cell(40, 15, "CARTA", 1, 1, "C", 1);

// Salto de línea
$pdf->Ln(5);

// Impresión de tarjetas por cada plato
$pdf->SetFillColor(127, 179, 213);
while ($row = mysqli_fetch_array($rs)) {
    // Imagen
    $pdf->Image('../multimedia/imagenes/platos/' . $row[4], null, null, 60, 60);
    // Mover cursor a la posición adecuada y trazar borde de imagen
    $pdf->SetY($pdf->GetY()-60);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 60, 60, 'D');
    // Celda invisible + Celda de nombre
    $pdf->SetFont("Times", "B", 14);     // Times New Roman Bold 15
    $pdf->Cell(60);
    $pdf->Cell(0, 10, utf8_decode($row[1]), 'LTR', 1, 'L', true);
    // Celda invisible + Celda de categoría
    $pdf->SetFont("Times", "I", 12);     // Times New Roman Italics 12
    $pdf->Cell(60);
    $pdf->Cell(0, 10, utf8_decode("Categoria: ".$row[9]), 'LR', 1, 'R', true);
    // Celda invisible + Celda de precio
    $pdf->SetFont("Times", "", 12);     // Times New Roman 12
    $pdf->Cell(60);
    $pdf->Cell(0, 10, "Precio regular: S/ ".$row[5], 'LBR', 1, 'L', true);
    // Celda de descripción
    $desc = utf8_decode($row[3]);
    $lineas = $pdf->WordWrap($desc, 125);
    $pdf->Cell(60);
    $pdf->MultiCell(0,30/$lineas, $desc,1);
    // Salto de línea
    $pdf->Ln(10);
}

// Cerrar conexión
mysqli_free_result($rs);
mysqli_close($cn);

// Mostrar reporte
$pdf->Output('carta-sazon_y_fuego.pdf', 'I');
?>