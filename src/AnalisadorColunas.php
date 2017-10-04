<?php

namespace Vios\Devops\AnalisadorCsv;

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
}