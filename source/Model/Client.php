<?php

namespace Source\Model;

use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\ClientObject;
use \Source\Model\ORM\Model as Schema;
use Source\Domain\Origin;

#[Entity("cliente")]
class Client extends Schema {

    #[Column(alias: "id", key: Column::PK)]
    private $cliente_id;
    #[Column(alias: "nome")]
    private $cliente_nome;
    #[Column(alias: "idOrigem")]
    private $cliente_id_origem;
    #[Column(alias: "cpf")]
    private $cliente_cpf;
    #[Column(alias: "sexo")]
    private $cliente_sexo;
    #[Column(alias: "estadoCivil")]
    private $cliente_estado_civil;
    #[Column(alias: "nacionalidade")]
    private $cliente_nacionalidade;
    #[Column(alias: "nomeMae")]
    private $cliente_nome_mae;
    #[Column(alias: "hash")]
    private $cliente_hash;
    #[Column(alias: "dataNascimento")]
    private $cliente_data_nascimento;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $cliente_data_insert;
    #[Column(alias: "ultimaAtualizacao", generatedValue: true)]
    private $cliente_data_update;


    /**
     * Retorna objeto do cliente para comparação
     * @param string $hash 
     * @return ClientObject|bool
     * @todo Trocar o nome
     * ![](https://avatars.githubusercontent.com/u/50432534?v=4)
     */
    public function getClient( string $hash ) : ClientObject|bool
    {
        $result = Schema::select(useAlias: true)->where("cliente_hash", $hash)->last("cliente_id");

        if($result){
            return (new ClientObject(
                id: $result->id,
                cpf : $result->cpf,
                nome: strtoupper($result->nome),
                sexo: strtoupper($result->sexo),
                estadoCivil: strtoupper($result->estadoCivil),
                nacionalidade: strtoupper($result->nacionalidade),
                nomeMae: strtoupper($result->nomeMae),
                dataNascimento: $result->dataNascimento,
            ));
            
        }

        return false;
    }

    public function findByHash( string $hash ) : object|bool
    {
        return Schema::select(useAlias: true)->where("cliente_hash", $hash)->one();
      
    }   

    public function findByCpf( $cpf ) : Object|bool
    {
        return Schema::select(useAlias: true)->where("cliente_cpf", $cpf)->one();
    }

    public function findIdByHash( string $hash ) : object|false
    {
        return Schema::select([
            "cliente_id" => "id"
        ])->where("cliente_hash", $hash)->one();
    }

    public function register(clientObject $clientObject) 
    {
        $this->cliente_nome = $clientObject->getNome(isNullable: true);
        $this->cliente_cpf = $clientObject->getCPF();
        $this->cliente_sexo = $clientObject->getSexo(isNullable: true);
        $this->cliente_estado_civil = $clientObject->getEstadoCivil(isNullable: true);
        $this->cliente_nacionalidade = $clientObject->getNacionalidade(isNullable: true);
        $this->cliente_nome_mae = $clientObject->getNomeMae(isNullable: true);
        $this->cliente_hash = $clientObject->getHash();
        $this->cliente_id_origem = Origin::getOrigin();
        $this->cliente_data_nascimento = $clientObject->getDataNascimento(isNullable: true);
        
        return $this->save();
    }

        /**
     * Atualiza dados do cliente
     */
    public function updateModel(array $data, int $id )
    {
        return $this->update($data)->where("cliente_id" , $id)->execute();
    }

}

