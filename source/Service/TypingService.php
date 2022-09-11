<?php

namespace Source\Service;

use Source\Model\Client;
use Source\Model\Proposal;
use Source\Router\Request;
use Source\Model\Attendance;
use Source\Model\ClientBank;
use Source\Model\ClientMail;
use Source\Model\Simulation;
use Source\Http\Response\API;
use Source\Attributes\Response;
use Source\Model\ClientAddress;
use Source\Model\ClientContact;
use Source\Model\BankCredencial;
use Source\Model\ClientDocument;
use Source\Model\ClientEmployer;
use Source\Attributes\Permission;
use Source\Api\APIGateway\Typing ;
use Source\Exception\ClientException;
use Source\Exception\ProposalException;
use Source\Api\APIGateway\CreditConditions;
use Source\Service\ValueObject\ProposalObject;
use Source\Domain\Token;

#[Response(API::class)]
class TypingService{

    #[Permission(
        level: Permission::CREATE,
        service: Permission::PROPOSAL
    )]
    public function proposalTyping(Request $request)
    {

        $inputs = $request->inputs();
        $dadosProposta = $inputs["dadosProposta"];
        $hashClient = $inputs['hashClient'];

        $md5Regex = '/^[a-f0-9]{32}$/';
        if(!preg_match($md5Regex, $hashClient)){
            throw new ClientException("Hash do cliente inválido");

        }else if (is_null($dadosProposta)){
            throw ProposalException::invalidField("dadosSimulacao");

        }   
        
        $userId = token::getUserId();
        $clientObject = (new Client())->getClient($hashClient);
        if(!$clientObject){
            throw new ProposalException("Cliente nao cadastrado", 400);
        }

        if(!$dadosProposta["idSimulacao"]){
            throw new ProposalException("informe o campo 'idSimulacao'", 400);
        }

        $clientId = $clientObject->getId();
        $attendance = new Attendance;

        $clientAddressObject = (new ClientAddress)->getClient($clientId);        
        $clientBankObject = (new ClientBank)->getClient($clientId);        
        $clientContactObject = (new ClientContact)->getClient($clientId);        
        $clientMailObject = (new ClientMail)->getClient($clientId);        
        $clientDocumentObject = (new ClientDocument)->getClient($clientId);        
        $clientEmployerObject = (new ClientEmployer)->getClient($clientId);        
        
        $simulationObject = (new Simulation)->getSimulation($dadosProposta["idSimulacao"]);
        
        if(!$simulationObject){
            throw new ProposalException("Simulação não encontrada", 400);
        }
        # Consulta atendimento que esta cadastrado na simulacao
        $attendance = (new Attendance)->find(
            id: $simulationObject->getIdAtendimento(),
            useAlias: true
        );

        if(
            !$attendance ||
            $attendance->idUsuario != $userId ||
            $attendance->status != Attendance::ATTENDANCE_IN_PROCESS
        ){
            throw new ProposalException("Atendimento registrado na simulacao informada nao esta ativa!", 400);
        }
                
        $creditConditions = new CreditConditions($simulationObject);
        $credentialObject = (new BankCredencial)->getCrencials(
            userId: Token::getUserId(), 
            bankId: $creditConditions->getIdBank()
           );

        $api = (new Typing(
            creditConditions: $creditConditions,
            clientObject: $clientObject,
            credentialObject: $credentialObject,
            clientAddressObject: $clientAddressObject,
            clientBankObject: $clientBankObject,
            clientContactObject: $clientContactObject,
            clientMailObject: $clientMailObject,
            clientDocumentObject: $clientDocumentObject,
            clientEmployerObject: $clientEmployerObject
        ))->send();
        
        $proposal = new Proposal;
        $proposal->register(
            new ProposalObject(
                idSimulacao: $simulationObject->getId(),
                codPropostaBanco: $api->getProposal(),
            )
        );

        return $api->toArray();

    }

    /**
     * Salva proposta no banco de dados
     * 
     * @param Request $request
     * @return array
     */
    
    #[Permission(
        level: Permission::CREATE,
        service: Permission::PROPOSAL
    )]
    public function saveBankProposal( Request $request ) : array
    {

        $inputs = $request->inputs();
        $proposalData =  new ProposalObject(
            idSimulacao: $inputs["idSimulacao"],
            codPropostaBanco: $inputs["codPropostaBanco"],
        );

        if(!$proposalData->getIdSimulacao()){
            throw new ProposalException("informe o campo 'idSimulacao'", 400); 

        }else if (!$proposalData->getCodPropostaBanco()){
            throw new ProposalException("informe o campo 'codPropostaBanco'", 400); 

        }
        
        $proposal = new Proposal;
        if($proposal->findByBankCode($proposalData->getCodPropostaBanco())){
            throw new ProposalException("Ja existe um registro com o codigo de proposta informado", 400);

        }

        $idProposal = $proposal->register(
           proposalObject: $proposalData
        );

        return [
            "message" => "Proposta cadastrada com sucesso",
            "data" => [
                "idProposta" =>  $idProposal
            ]
        ];

    }

}