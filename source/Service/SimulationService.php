<?php
 
namespace Source\Service;

use Source\Model\Client;
use Source\Router\Router;
use Source\Router\Request;
use Source\Model\Attendance;
use Source\Model\Simulation;
use Source\Http\Response\API;
use Source\Model\TableWebcred;
use Source\Attributes\Response;
use Source\Attributes\Permission;
use Source\Exception\ClientException;
use Source\Exception\SimulationException;
use Source\Api\APIGateway\CreditConditions;
use Source\Service\ValueObject\SimulationObject;
use Source\Api\APIGateway\Simulation as ApiGateway;
use Source\Domain\Token as token;

#[Response(API::class)]
class SimulationService{

    public function __construct( private Router $router ) {}
    
    #[Permission(
        service: Permission::SIMULATION,
        level: Permission::READ
        )]
    public function simulate( Request $request ){
    
        $inputs = $request->inputs();
        $permissionType = $request->middleware("Permission")["type"];

        if($permissionType == Permission::ROBOT){ #Robo nao possui trava de atenndimento
            $dadosSimulacao = $inputs["dadosSimulacao"];

            $md5Regex = '/^[a-f0-9]{32}$/';
            if(!preg_match($md5Regex, $inputs['hashClient'])){
                throw new ClientException("Hash do cliente inválido");

            }else if (is_null($dadosSimulacao)){
                throw SimulationException::invalidField("dadosSimulacao");
  
            }
            /**
             * Objeto contendo os dados necessarios para simulaçao
             */
            $simulationObject = new SimulationObject( 
                idTipo: $dadosSimulacao["idTipo"],
                valor: $dadosSimulacao["valor"],
                prazo: $dadosSimulacao["prazo"],
                margem: $dadosSimulacao["margem"],
                renda: $dadosSimulacao["renda"],
                seguro: (int)$dadosSimulacao["seguro"],
                idTabelaBanco : $dadosSimulacao["idTabelaBanco"]
            );            
            /**
             * Objeto que a simulaçao do APIGATEWAY exige para ser feita a simulaçao
             */
            $creditConditions = new CreditConditions($simulationObject);
            /**
             * Retorna um objeto do tipo cliente de acordo com a hash enviada
             */
            $clientObject = (new Client())->getClient($inputs['hashClient']);

            if(!$clientObject){
                throw new ClientException("Cliente não encontrado");
            }
            /**
             * Simulaçao efetuada
             */
            $api = (new ApiGateway( 
                $clientObject,$creditConditions
            ))->send(); 

            return $api->toArray(); # resposta apigateway convertida para array
        }
        
        ## usuario nao tem permissao para simular caso o cliente esteja em atendimento com outro usuario!!
        $token = new Token;
        $token->getUserId();
        $this->router->redirect("/erro/501");
    }

    /**
     * @todo implementar validaçao do idPortabilidade (oneday ..)
     */
    public function register( Request $request )
    {
        $inputs = $request->inputs();
        $dadosSimulacao = $inputs["dadosSimulacao"]; 
        $hashClient = $inputs["hashClient"];   

        $userId = token::getUserId();
        $client = (new Client())->findIdByHash($hashClient);

        if(!$client){
            throw new SimulationException("Cliente não encontrado");

        }

        if(!isset($dadosSimulacao)){
            throw new SimulationException("informe 'dadosSimulacao'");
        }

        if(!is_numeric($dadosSimulacao["idProduto"])){
            throw SimulationException::invalidField("idProduto");
        }

        $attendanceModel = new Attendance;
        $attendance = $attendanceModel->findByProduct(
            idUser: $userId,
            idProduct: $dadosSimulacao["idProduto"],
            idClient: $client->id
        );

        if(!$attendance){
            throw new SimulationException("Usuario nao possui atendimento ativo com o cliente para este produto");
            
        }
            
        if(!isset($dadosSimulacao["idTabelaWeb"])){
            throw new SimulationException("Informe um id tabela Webcred");
        }
       

        $tableWeb = new TableWebcred;
        if(!$tableWeb->find($dadosSimulacao["idTabelaWeb"])){
            throw new SimulationException("Tabela webcred nao cadastrada");

        }

        $simulationObject = new SimulationObject( 
            idTipo: $dadosSimulacao["idTipo"],
            valor: $dadosSimulacao["valor"],
            descricao: $dadosSimulacao["descricao"],
            idAtendimento: $attendance->idAtendimento,
            idPortabilidade: $dadosSimulacao["idPortabilidade"],
            prazo: $dadosSimulacao["prazo"],
            margem: $dadosSimulacao["margem"],
            renda: $dadosSimulacao["renda"],
            idTabelaBanco : $dadosSimulacao["idTabelaBanco"],
            idTabelaWeb : $dadosSimulacao["idTabelaWeb"]
        );        

        $simulation = new Simulation;
        $simulationId = $simulation->register($simulationObject);

        return [
            "message" => "simulacao registrada com sucesso",
            "data" => [
                "id" => $simulationId
            ]
        ];
    
    }   

    public function consultLastSimulation(Request $request){
        $attendanceId = $request->inputs()["idAtendimento"];

        if(!is_numeric($attendanceId)){
            throw new SimulationException("Id do atendimento inválido!", 400);
        }

        $simulation = (new Simulation)->findByAttendanceId($attendanceId);

        if(!$simulation){
            throw new SimulationException("Nenhuma simulação encontrada para esse atendimento", 400);
        }

        return [
            "message" => "Simulação encontrada com sucesso",
            "data" => [
                "simulacao" => $simulation
            ]
        ];

    }
}