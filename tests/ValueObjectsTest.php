<?php

use Tests\ValueObjectsExecuter;
use PHPUnit\Framework\TestCase;
use Source\Service\ValueObject;

class ValueObjectsTest extends TestCase{
   
    /**
     * testa o retorno correto dos valuesObjects
     * @return array
     */
    public function provider()
    {
        return [
            [   #valueObject
                new ValueObject\ClientObject(
                    nome: "teste meu ovo",
                    cpf: "39988129890",
                    sexo: "M",
                    estadoCivil: "SOLTEIRO",
                    nacionalidade: "BRASILEIRO",
                    nomeMae: "maria do carmo",
                    hash: "1234667994225111",
                    dataNascimento: "1998-01-23"
                ),[ #Retorno esperado
                    "getNome" => "teste meu ovo",
                    "getCpf" => 39988129890,
                    "getSexo" => "M",
                    "getEstadoCivil" => "SOLTEIRO",
                    "getNacionalidade" => "BRASILEIRO",
                    "getNomeMae" => "maria do carmo",
                    "getHash" => "1234667994225111",
                    "getDataNascimento" => "1998-01-23"
                ]
            ],
            [
                new ValueObject\ClientUserObject(
                    idCliente: 1,
                    usuario: "teste.teste",
                    senha: "teste.teste"
                ),
                [
                    "getIdClient" => 1,
                    "getUser" => "teste.teste",
                    "getPassword" => "teste.teste"
                ]

            ],
            [
                new ValueObject\ClientMailObject(
                    idCliente: 1,
                    email: "william_ferreira@yahoo.com"
                ),
                [
                    "getIdClient" => 1,
                    "getMail" => "william_ferreira@yahoo.com"
                ]
            ],
            [
                new ValueObject\ClientContactObject(
                    idCliente: 1,
                    ddd: 11,
                    telefone: 999999999
                ),
                [
                    "getIdClient" => 1,
                    "getDdd" => 11,
                    "getTelefone" => 999999999
                ]
            ],
            [
                new ValueObject\ClientBankObject(
                    idCliente: 1,
                    numeroBanco: "123",
                    agencia: "2501",
                    conta: "22364",
                    digitoConta: "1",
                    tipoConta: "CPI",
                    bancoAverbacao: true
                ),
                [
                    "getIdClient" => 1,
                    "getNumeroBanco" => "123",
                    "getAgencia" => "2501",
                    "getConta" => "22364",
                    "getDigitoConta" => "1",
                    "getTipoConta" => "CPI",
                    "getBancoAverbacao" => 1,

                ]
            ],
            [
                new ValueObject\ClientEmployerObject(
                    idCliente: 1,
                    especie: "1",
                    uf: "SP",
                    matricula: "123"
                ),
                [
                    "getIdClient" => 1,
                    "getSpecie" => 1,
                    "getUf" => "SP",
                    "getSubscription" => "123"
                ]
            ],
            [
                new ValueObject\ClientAddressObject(
                    idCliente: 1,
                    rua: "Rua teste",
                    numero: "123",
                    complemento: "teste",
                    bairro: "teste",
                    cep: "12345678",
                    cidade: "teste",
                    uf: "SP"
                ),
                [
                    "getIdClient" => 1,
                    "getRua" => "Rua teste",
                    "getNumero" => "123",
                    "getComplemento" => "teste",
                    "getBairro" => "teste",
                    "getCep" => "12345678",
                    "getCidade" => "teste",
                    "getUf" => "SP"
                ]
            ],
            [
                new ValueObject\ClientDocumentObject(
                    idCliente: 1,
                    documento: "123456789",
                    idTipo:1,
                ),
                [
                    "getIdClient" => 1,
                    "getDocument" => "123456789",
                    "getIdTipo" => 1
                ]
            ],
            [
                new ValueObject\AcceptObject(
                    idCliente: 1,
                    finalidade: "COÇAR MEU OVO",
                    idAcao: 1,
                    idOrigem: 1
                ),
                [
                    "getIdCliente" => 1,
                    "getFinalidade" => "COÇAR MEU OVO",
                    "getIdOrigem" => 1,
                    "getIdAcao" => 1
                ]
            ],
            [
                new ValueObject\AttendanceLogObject(
                    idAcao: 1,
                    idUsuario: 1,
                    idAtendimento: 2
                ),
                [
                    "getIdAcao" => 1,
                    "getIdUsuario" => 1,
                    "getIdAtendimento" => 2
                ]
            ],
            [
                new ValueObject\AttendanceObject(
                    idAtendimento: 1,
                    idCliente: 1,
                    idUsuario: 1,
                    status: 1,
                    idCanal: 1,
                    idFinalizacao: 1,
                    dataFinalizacao: "2022-01-23",
                    idTabulacao: 1,
                    idEtapa:1,
                    idProduto: 1,
                    idAtendimentoAnterior: 2
                    
                ),
                [
                    "getIdAtendimentoAnterior" => 2,
                    "getIdAtendimento" => 1,
                    "getIdCliente" => 1,
                    "getIdUsuario" => 1,
                    "getStatus" => 1,
                    "getIdCanal" => 1,
                    "getIdFinalizacao" => 1,
                    "getDataFinalizacao" => "2022-01-23",
                    "getIdTabulacao" => 1,
                    "getIdProduto" => 1,
                    "getIdEtapa" => 1
                ]
            ],
            [
                new ValueObject\ConvenantObject(
                    nome: "GOVERNO DO ESTADO DE MINAS",
                    codWebcred: 123,
                    codApiGateway: 123,
                    cnpj: 4455555555555,
                ),
                [
                    "getNome" => "GOVERNO DO ESTADO DE MINAS",
                    "getCodWebcred" => 123,
                    "getCodApiGateway" => 123,
                    "getCnpj" => 4455555555555
                ]
            ],
            [
                new ValueObject\CredentialObject(
                    userOriginId: 1,
                    userOriginCpf: "123456789",
                    access: 12345,
                    password: 12345,
                    bank: 1
                ),
                [
                    "getUserOriginId" => 1,
                    "getUserOriginCpf" => "123456789",
                    "getAccess" => 12345,
                    "getPassword" => 12345,
                    "getBank" => 1
                ]
            ],
            [
                new ValueObject\DepartmentObject(
                    descricao: "Teste"
                ),
                [
                    "getDescricao" => "Teste"
                ]
            ],
            [
                new ValueObject\ProposalObject(
                    idSimulacao: 2,
                    codPropostaBanco: 1,
                    idPropostaWebcred: 1,
                    codStatusBanco: 1,
                    dataAverbacao: "2022-01-23",
                    dataPagamento: "2022-01-23",
                    situacao: "Teste",
                    statusFormalizacao: "meupa",
                    idFormalizacao: 1
                ),
                [
                    "getIdSimulacao" => 2,
                    "getCodPropostaBanco" => 1,
                    "getIdPropostaWebcred" => 1,
                    "getCodStatusBanco" => 1,
                    "getDataAverbacao" => "2022-01-23",
                    "getDataPagamento" => "2022-01-23",
                    "getSituacao" => "Teste",
                    "getStatusFormalizacao" => "meupa",
                    "getIdFormalizacao" => 1
                
                ]
            ],
            [
                new ValueObject\SimulationObject(
                    valor: "2300",
                    descricao: "MEUPA",
                    idAtendimento: 1,
                    idTabelaBanco: 1,
                    idPortabilidade: 1,
                    idTabelaWeb: 1,
                    idTipo: 1,
                    margem: 20,
                    renda: 10000,
                    prazo: 10,
                    seguro: 1
                ),
                [
                    "getValor" => "2300",
                    "getDescricao" => "MEUPA",
                    "getIdAtendimento" => 1,
                    "getIdTabelaBanco" => 1,
                    "getIdPortabilidade" => 1,
                    "getIdTabelaWeb" => 1,
                    "getIdTipo" => 1,
                    "getMargem" => 20,
                    "getRenda" => 10000,
                    "getPrazo" => 10,
                    "getSeguro" => 1
                
                ]
            ],
            [
                new ValueObject\TabLogObject(
                    id: 1,
                    idAtendimento: 1,
                    idEtapa: 1,
                    idProduto: 1,
                    idTabulacao: 1 
                ),
                [
                    "getId" => 1,
                    "getIdAtendimento" => 1,
                    "getIdEtapa" => 1,
                    "getIdProduto" => 1,
                    "getIdTabulacao" => 1

                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider provider
     * @return void
     * @param object $valueObject
     * @param array $expected
     */
    public function value_object_test_true(object $valueObject, array $expected )
    {
        $executer = new ValueObjectsExecuter(
           instance: $valueObject
        );

        if(!isset($expected['getId'])){
            $executer->unsetGetter("getId");
        }
       
        $this->assertEquals(
            expected: $expected,
            actual: $executer->testWithNullEnabled()
        );
        
    }

      


   

}