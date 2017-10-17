<?php

namespace Vios\Devops\AnalisadorCsv;

use League\Csv\Modifier\MapIterator;

class AnalisadorColunas
{
    public function analisaArray(\Iterator $linhas) : array
    {
        $colunas = [];
        $linhasAnalisadas = 0;

        foreach ($linhas as $linha) {
            foreach($linha as $key => $dado) {
                if(!isset($colunas[$key])) {
                    $colunas[$key] = [
                        'nome'  => $key,
                        'total' => 0,
                        'min'   => "",
                        'max'   => ""
                    ];
                }

                $dado = trim($dado);
                if(empty($dado)) {
                    continue;
                }

                $colunas[$key]['total']++;

                if($dado < $colunas[$key]['min'] or $colunas[$key]['min'] === "") {
                    $colunas[$key]['min'] = $dado;
                }

                if($dado > $colunas[$key]['max']) {
                    $colunas[$key]['max'] = $dado;
                }
            }

            $linhasAnalisadas++;

            if($linhasAnalisadas % 100 === 0) {
                echo "Analisadas {$linhasAnalisadas}\n";
            }
        }

        return $colunas;
    }

    public function groupByColuna(\Iterator $linhas, $keyColuna, $min = 1) : array
    {
        $array = iterator_to_array($linhas, false);

        if(!isset($array[0][$keyColuna])) {
            throw new \InvalidArgumentException("Chave {$keyColuna} não encontrada no conjunto de dados");
        }

        $dadosColuna = array_column($array, $keyColuna);
        $contagem = array_count_values($dadosColuna);

        $arrayFinal = [];
        foreach ($contagem as $key => $value) {
            if($value < $min) {
                continue;
            }

            $arrayFinal[] = [
                'valor' => $key,
                'contagem' => $value
            ];
        }

        return $arrayFinal;
    }
}