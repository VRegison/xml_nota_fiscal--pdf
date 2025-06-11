<?php
require 'vendor/autoload.php';

// Caminho completo da classe DANFE
$danfe = new \NFePHP\DA\NFe\Danfe(file_get_contents('teste.xml'));
$pdf = $danfe->render();

// Salva o PDF
file_put_contents('danfe.pdf', $pdf);
