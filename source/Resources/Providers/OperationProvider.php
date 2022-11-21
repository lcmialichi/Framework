<?php

namespace Source\Resources\Providers;

use Source\Enum\Banks;
use Source\Enum\Product;
use Source\Enum\Operation;
use Source\Request\Request;
use Source\Container\Container;
use Source\DTO\CreditConditions;

class OperationProvider extends Provider
{
    public function register(Request $request)
    {   
    
        if (isset($request->dadosProposta["idSimulacao"])) {
            $idSimulacao = $request->dadosProposta["idSimulacao"];
            $simulation = (new \Source\Model\Simulation)->find($idSimulacao);

            if (isset($simulation->simulacao_id_tabela_banco)) {
                $bankTable = $simulation->simulacao_id_tabela_banco;
            }

        }

        if(isset($request->dadosSimulacao["idTabelaBanco"])){
            $bankTable = $request->dadosSimulacao["idTabelaBanco"];
        }

        if(!isset($bankTable)){
            return;
        }
        
        $table = new \Source\Model\Table;
        $table = $table->find($bankTable);
        if(!$table || $table->banco_status != 1){
           throw new \InvalidArgumentException("Tabela do banco não encontrada");
        }

        $this->app->bind(
            Banks::class, fn() => Banks::from($table->banco_id_banco)
        );
        $this->app->bind(
            Operation::class,fn() => Operation::from($table->banco_id_operacao)
        );
        $this->app->bind(
            Product::class, fn() => Product::tryFrom($table->banco_id_produto)
        );
        $this->app->bind(
            CreditConditions::class, function () use ($table){
                return CreditConditions::make(
                    descricao: $table->banco_descricao,
                    codigo: $table->banco_codigo,
                    status: $table->banco_status,
                    orgao: $table->banco_id_orgao,
                    convenio: $table->banco_id_convenio,
                    operacao: $table->banco_id_operacao,
                    produto: $table->banco_id_produto,
                    banco: $table->banco_id_banco,
                );
            }
        );
    
        
    }
}
