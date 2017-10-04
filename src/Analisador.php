<?php

namespace Vios\Devops\AnalisadorCsv;

use League\CLImate\Util\Reader\ReaderInterface;
use League\Csv\Reader;

class Analisador
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var array
     */
    private $colunasAnalisadas;

    /**
     * @var array
     */
    private $cabecalho;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
        $this->cabecalho = $this->reader->fetchOne();

        $analisadorColunas = new AnalisadorColunas();
        $this->colunasAnalisadas = $analisadorColunas->analisaArray($this->reader->fetchAssoc());
    }

    public function pegaCabecalho() : array
    {
        return $this->cabecalho;
    }

    public function pegaColunas() : array
    {
        return $this->colunasAnalisadas;
    }

    public function pegaColunasComDadosSignificativos() : array
    {
        $colunasComDados = [];
        foreach ($this->colunasAnalisadas as $coluna) {
            $minEmaxSaoDiferentes = $coluna['min'] !== $coluna['max'];

            if($coluna['total'] > 1 && $minEmaxSaoDiferentes) {
                $colunasComDados[] = $coluna;
            }
        }

        usort($colunasComDados, function($a, $b) {
            return ($a['total'] > $b['total']) ? -1 : 1;
        });

        return $colunasComDados;
    }
}