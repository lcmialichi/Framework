<?php

namespace Source\Service;

use Source\Model\Client;
use Source\Model\Product;
use Source\Router\Router;
use Source\Router\Request;
use Source\Model\Attendance;
use Source\Http\Response\API;
use Source\Log\AttendanceLog;
use Source\Attributes\Response;
use Source\Attributes\Permission;
use Source\Exception\AttendanceError;
use Source\Exception\AttendanceException;
use Source\Enum\AttendanceLogAction as Action;
use Source\Service\ValueObject\AttendanceObject;
use Source\Domain\Token;

#[Response(API::class)]
class AttendanceService{

    public function __construct(private Router $router)
    {
    }

    #[Permission(
        service: Permission::ATTENDANCE, 
        level: Permission::CREATE
    )]
    public function register(Request $request){

        $inputs = $request->inputs();
        $permissionType = $request->middleware("Permission")['type'];
        $hashClient = $inputs["hashCliente"];

        //valida se a hash tem o formato valido
        if(!preg_match("/^[0-9a-f]{32}$/", $hashClient)){
            throw new AttendanceException("Hash do cliente inválido", 400);

        }   

        //verificar se o cliente existe
        $clientId = (new Client())->findIdByHash($hashClient);

        if(!$clientId){
            throw new AttendanceException("Cliente não encontrado", 400);
        }

        $attendanceObject = new AttendanceObject(
            idCliente: $clientId->id,
            idUsuario: Token::getUserId(),
            idProduto: $inputs["idProduto"],
            idAtendimentoAnterior: $inputs["idAtendimentoAnterior"] ?? null
        );

        $product = (new Product)->find($attendanceObject->getIdProduto());
        if(!$product){
            throw new AttendanceException("Produto informado nao cadastrado!", 400);

        }

        $attendanceModel = new Attendance;
        $attendances = $attendanceModel->findByUserAndClient(
            idUsuario: Token::getUserId(),
            idCliente: $attendanceObject->getIdCliente()
        );

        if($attendances){
          foreach($attendances as $attendance){
            if(
                $attendance->idProduto == $inputs["idProduto"] &&
                $attendance->status == Attendance::ATTENDANCE_IN_PROCESS)
            {
                throw new AttendanceException("Cliente e usuario ja possuem atendimento com o produto especificado em andamento.");
            }
          }

        }
        
        /** EXECUCAO APENAS PARA O ROBO */
        if($permissionType == Permission::ROBOT)
        {
            $attendanceId = $attendanceModel->register($attendanceObject);
            if(!$attendanceId){
                throw new AttendanceError("Nao foi possivel registrar atendimento");

            }

            /**LOG */
            AttendanceLog::save(
                acao: Action::CREATED_ATTENDANCE,
                idAtendimento: $attendanceId
            );

            return [
                "message" => "Atendimento registrado com sucesso!",
                "data" => [
                    "idAtendimento" => $attendanceId
                ]
            ];
         
    
        }

        //se não for robô, verificar se o cliente já está sendo atendido por algum consultor
        // nao esquecer de validar se o atendimento esta com corretor ou robo

        $this->router->redirect("/erro/501");

    }

    /**
     * @todo deixar apenas para o robo consultar
     *
     * @param Request $request
     * @return array
     */
    #[Permission(
        service: Permission::ATTENDANCE, 
        level: Permission::READ
    )]
    public function consultByUserHash(Request $request){

        $inputs = $request->inputs();
        if(!$inputs["hash"]){
            throw new AttendanceException("Hash não informado", 400);
        }

         //valida se a hash tem o formato valido
         if(!preg_match("/^[0-9a-f]{32}$/",$inputs["hash"])){
            throw new AttendanceException("Hash do cliente inválido", 400);

        }   

        $client = (new Client())->findIdByHash($inputs["hash"]);

        $attendance = new Attendance;
        $attendance = $attendance->findByUserAndClient(
            idUsuario: Token::getUserId(),
            idCliente: $client->id
         );
        
        if(!$attendance){
            throw new AttendanceException("Não foi possível encontrar o registro", 400);

        }
         /**
         * Remove o Id do cliente do objeto
         */
        array_walk($attendance, function($item){
            unset($item->idCliente);
        });


        return [
            "message" => "Lista gerada com sucesso!",
            "data" => [
                "atendimentos" => $attendance
            ]
        ];

    }



}