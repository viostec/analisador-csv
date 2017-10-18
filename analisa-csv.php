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

$colunasAnalisadas = $analisador->pegaColunas();
$totalColunasAnalisadas = count($colunasAnalisadas);

$cli->flank("Todas as {$totalColunasAnalisadas} Colunas");
$cli->table($colunasAnalisadas);

$writter = \League\Csv\Writer::createFromPath(__DIR__ . "/colunas.csv", "w+");
$writter->insertAll($colunasAnalisadas);

//$cli->flank("Colunas com dados significativos");
//$cli->table($analisador->pegaColunasComDadosSignificativos());

