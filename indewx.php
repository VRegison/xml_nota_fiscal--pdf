<?php
require 'vendor/autoload.php';

/**
 * Gera um PDF DANFE a partir do caminho de um arquivo XML.
 *
 * @param string $caminhoXml Caminho do arquivo XML da nota fiscal.
 * @param string|null $caminhoPdf Saída para salvar o PDF gerado. Se nulo, salva com mesmo nome do XML e extensão .pdf na pasta 'pdfs'.
 * @return bool|string Retorna o caminho do PDF gerado ou false em caso de erro.
 */
function gerarDanfePdf(string $caminhoXml, $idPedido,?string $caminhoPdf = null)
{
    if (!file_exists($caminhoXml)) {
        error_log("Arquivo XML não encontrado: $caminhoXml");
        return false;
    }

    try {
        $conteudoXml = file_get_contents($caminhoXml);
        $danfe = new \NFePHP\DA\NFe\Danfe($conteudoXml);
        $conteudoPdf = $danfe->render();

        if (is_null($caminhoPdf)) {
            // Garante que a pasta 'pdfs' existe
            if (!is_dir('pdfs')) {
                mkdir('pdfs', 0755, true);
            }
            // Pega só o nome do arquivo XML e troca extensão para .pdf
            $nomeArquivo = basename($caminhoXml, '.xml');
            $caminhoPdf = "pdfs/{$idPedido}.pdf";
        }

        file_put_contents($caminhoPdf, $conteudoPdf);

        return $caminhoPdf;
    } catch (\Exception $e) {
        error_log("Erro ao gerar DANFE para $caminhoXml: " . $e->getMessage());
        return false;
    }
}

// Exemplo de uso para vários arquivos
$arquivosXml = ['1.xml', '2.xml', '3.xml', '4.xml', '5.xml', '6.xml'];

foreach ($arquivosXml as $arquivoXml) {
   $idPedido = random_int(288888, 3555555);
    $pdfGerado = gerarDanfePdf($arquivoXml,$idPedido);
    if ($pdfGerado !== false) {
        echo "PDF gerado com sucesso: $pdfGerado\n";
    } else {
        echo "Falha ao gerar PDF para o arquivo: $arquivoXml\n";
    }
}
