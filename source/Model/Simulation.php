<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;
use Source\Service\ValueObject\SimulationObject;

#[Entity("simulacao")]
class Simulation extends Schema
{

    #[Column(alias: "id", key: Column::PK)]
    private $simulacao_id;
    #[Column(alias: "valor")]
    private $simulacao_valor;
    #[Column(alias: "descricao")]
    private $simulacao_descricao;
    #[Column(alias: "idAtendimento")]
    private $simulacao_id_atendimento;
    #[Column(alias: "idTabelaBanco")]
    private $simulacao_id_tabela_banco;
    #[Column(alias: "idPortabilidade")]
    private $simulacao_id_portabilidade;
    #[Column(alias: "idTabelaWeb")]
    private $simulacao_id_tabela_web;
    #[Column(alias: "idTipo")]
    private $simulacao_id_tipo;
    #[Column(alias: "margem")]
    private $simulacao_margem;
    #[Column(alias: "renda")]
    private $simulacao_renda;
    #[Column(alias: "prazo")]
    private $simulacao_prazo;
    #[Column(alias: "dataCriacao", generatedValue: true)]
    private $simulacao_data_insert;
    #[Column(alias: "dataAlteracao", generatedValue: true)]
    private $simulacao_data_update;

    public function getSimulation( int $idSimulation ) : bool|SimulationObject
    {
        $result = Schema::find(id: $idSimulation , useAlias: true);

        if($result){
            return new SimulationObject( 
                id: $result->id,
                valor: $result->valor,
                descricao: $result->descricao,
                idAtendimento: $result->idAtendimento,
                idTabelaBanco: $result->idTabelaBanco,
                idPortabilidade: $result->idPortabilidade,
                idTabelaWeb: $result->idTabelaWeb,
                idTipo: $result->idTipo,
                margem: $result->margem,
                renda: $result->renda,
                prazo: $result->prazo,
                seguro: $result->seguro
            );
        }
        return false;
    
    }

    /**
     * @return array|object|bool
     */
    public function findSimulationType(?int $id = null) : array|object|bool 
    {
        $query = Schema::table("simulacao_tipo")->select([
            "tipo_id" => "id",
            "tipo_descricao" => "descricao",
            "tipo_data_insert" => "dataCadastro",
            "tipo_data_update" => "dataAlteracao"

        ]);

        if($id !== null){
            return $query->where("tipo_id", $id)->one();
        }

        return $query->execute();
    }

    public function findByAttendanceId(int $id){
        return Schema::select(useAlias: true)->where("simulacao_id_atendimento", $id)->last();
    }

    public function register(SimulationObject $simulationObject)
    {
        $this->simulacao_valor = $simulationObject->getValor(isNullable: true);
        $this->simulacao_descricao = $simulationObject->getDescricao();
        $this->simulacao_id_atendimento = $simulationObject->getIdAtendimento();
        $this->simulacao_id_tabela_banco = $simulationObject->getIdTabelaBanco();
        $this->simulacao_id_tabela_web = $simulationObject->getIdTabelaWeb();
        $this->simulacao_id_tipo = $simulationObject->getIdTipo();
        $this->simulacao_renda = $simulationObject->getRenda(isNullable: true);
        $this->simulacao_prazo = $simulationObject->getPrazo(isNullable: true);
        $this->simulacao_margem = $simulationObject->getMargem(isNullable: true);
        $this->simulacao_id_portabilidade = $simulationObject->getIdPortabilidade(isNullable: true);
        return $this->save();
    }
}