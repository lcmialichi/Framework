<?php

namespace Source\Model;

use Source\Model\ORM\Entity;
use Source\Model\ORM\Column;
use \Source\Service\ValueObject\ClientBankObject;
use \Source\Model\ORM\Model as Schema;
use Source\Domain\Origin;

#[Entity("cliente_dados_bancarios")]
class ClientBank extends Schema 
{
    #[Column(alias: "id", key: Column::PK)]
    private $banco_id;
    #[Column(alias: "idCliente")]
    private $banco_id_cliente;
    #[Column(alias: "numeroBanco")]
    private $banco_numero_banco;
    #[Column(alias: "agencia")]
    private $banco_agencia;
    #[Column(alias: "conta")]
    private $banco_conta;
    #[Column(alias: "digitoConta")]
    private $banco_digito_conta;
    #[Column(alias: "tipoConta")]
    private $banco_tipo_conta;
    #[Column(alias: "idOrigem")]
    private $banco_id_origem;
    #[Column(alias: "bancoAverbacao")]
    private $banco_averbacao;
    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $banco_data_insert;
    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $banco_data_update;

    /**
     * Retorna objeto do endereço do cliente para comparação
     * @param string $hash 
     * @return ClientBankObject|bool
     * @todo Trocar o nome
     * ![](https://avatars.githubusercontent.com/u/50432534?v=4)
     */
    public function getClient( int $id ) : ClientBankObject|bool
    {
        $result = Schema::select(useAlias: true)->where("banco_id_cliente", $id)->last("banco_id");

        if($result){
            return new ClientBankObject(
                idCliente: $result->idClient,
                numeroBanco: $result->numeroBanco,
                agencia: $result->agencia,
                conta: $result->conta,
                digitoConta: $result->digitoConta,
                tipoConta: strtoupper($result->tipoConta),
                bancoAverbacao: $result->bancoAverbacao
            );
        }
        return false;
    }

    public function findByClientId( int $id ){
        return Schema::select(useAlias: true)->where("banco_id_cliente", $id)->get();
    }

    public function register(ClientBankObject $clientBankObject)
    {
        $this->banco_id_cliente = $clientBankObject->getIdClient();
        $this->banco_numero_banco = $clientBankObject->getNumeroBanco();
        $this->banco_agencia = $clientBankObject->getAgencia();
        $this->banco_conta = $clientBankObject->getConta();
        $this->banco_digito_conta = $clientBankObject->getDigitoConta();
        $this->banco_tipo_conta = $clientBankObject->getTipoConta();
        $this->banco_averbacao = $clientBankObject->getBancoAverbacao();
        $this->banco_id_origem = Origin::getOrigin();

        return $this->save();
    }

    public function updateModel(array $data, int $id )
    {
        return $this->update($data)->where("banco_id_cliente" , $id)->execute();
    }

}