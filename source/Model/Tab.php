<?php

namespace Source\Model;

use Source\Model\ORM\Model as Schema;
use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;

#[Entity("atendimento_tabulacao")]
class Tab extends Schema {

    #[Column(alias: "id", key: Column::PK)]
    private $tabulacao_id;
    
    #[Column(alias: "descricao")]
    private $tabulacao_descricao;

    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $tabulacao_data_insert;

    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $tabulacao_data_update;

    /**
     * Encontra a configracao da tabulacao
     *
     * @return object|bool
     */
    public function findByConfig(int $idTab, int $idProduct) :object|bool
    {
        return Schema::select(
            [
                "tabulacao_id_tabulacao" => "idTabulacao",
                "tabulacao_id_etapa" => "idEtapa",
                "etapa_descricao" => "etapaDescricao",
                "tabulacao_id_produto" => "idProduto",
                "produto_nome" => "produtoNome",
                "tabulacao_descricao" => "TabulacaoDescricao",
                "t1.tabulacao_finaliza" => "finaliza",
                "t1.tabulacao_tempo_limite" => "tempoLimite"
            ]
        )->join("produto_tabulacao as t1", "t1.tabulacao_id_tabulacao", "=", "tabulacao_id" )
                                ->join("atendimento_etapa as t3", "t3.etapa_id" , "=", "tabulacao_id_etapa")
                                ->join("produto as t2", "t2.produto_id", "=", "t1.tabulacao_id_produto")
                                ->where("t1.tabulacao_id_tabulacao", $idTab)
                                ->where("t1.tabulacao_id_produto", $idProduct)
                                ->one();
        
    }


}