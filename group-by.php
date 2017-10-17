<?php

use Vios\Devops\AnalisadorCsv\Analisador;

require_once __DIR__ . "/vendor/autoload.php";

$cli = new League\CLImate\CLImate();

$cli->arguments->add([
    "arquivo" => [
        'prefix'      => 'a',
        'longPrefix'  => 'arquivo',
        'description' => 'Arquivo à ser analisado',
        'required'    => true,
    ],

    "coluna" => [
        'prefix'      => 'c',
        'longPrefix'  => 'coluna',
        'description' => 'Coluna a ser analisada pelo Group by',
        'required'    => true,
    ]
]);

$cli->arguments->parse();
$nomeArquivo = $cli->arguments->get('arquivo');
$caminhoArquivo = __DIR__ . "/" . $nomeArquivo;
if(!file_exists($caminhoArquivo)) {
    throw new \InvalidArgumentException("Arquivo {$caminhoArquivo} não localizado");
}

$reader = \League\Csv\Reader::createFromPath($caminhoArquivo);
$analisador = new \Vios\Devops\AnalisadorCsv\AnalisadorColunas();

$coluna = $cli->arguments->get('coluna');

$cli->flank("");
$dados = $analisador->groupByColuna($reader->fetchAssoc(), $coluna, 2);
$cli->info("Total de Registros:" . count($dados));
$cli->table($dados);