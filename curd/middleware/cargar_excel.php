<?php
require 'C:\laragon\www\curd\vendor\autoload.php'; // Asegúrate de que la ruta sea correcta

use PhpOffice\PhpSpreadsheet\IOFactory;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == 0) {
        $fileTmpPath = $_FILES['excelFile']['tmp_name'];
        
        try {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Mostrar el contenido (ejemplo)
            /* echo "<pre>";
            print_r($rows);
            echo "</pre>"; */

        } catch (Exception $e) {
            echo "Error al leer el archivo: " . $e->getMessage();
        }
    } else {
        echo "No se seleccionó ningún archivo o hubo un error en la carga.";
    }
}
?>
