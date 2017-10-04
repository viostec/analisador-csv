<?php

use Vios\Devops\AnalisadorCsv\Analisador;
use Vios\Devops\AnalisadorCsv\AnalisadorColunas;

require_once __DIR__ . "/vendor/autoload.php";

$cli = new League\CLImate\CLImate();

$cli->arguments->add([
    "arquivo" => [
        'prefix'      => 'a',
        'longPrefix'  => 'arquivo',
        'description' => 'Arquivo à ser analisado',
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
$analisador = new Analisador($reader);

$cli->flank("Colunas");
$cli->table($analisador->pegaColunasComDadosSignificativos());