<?php

namespace Source\Service;

use Source\Attributes\Response;
use Source\Exception\ProposalException;
use Source\Http\Response\API;
use Source\Model\Attendance;
use Source\Model\Proposal;
use Source\Model\Simulation;
use Source\Router\Request;
use Source\Router\Router;

#[Response(API::class)]
class ProposalService{

    public function __construct( private Router $router ) {}

    public function consultByAttendance(Request $request){
        $inputs = $request->inputs();

        $attendanceId = $inputs["idAtendimento"];

        if(!is_numeric($attendanceId)){
            throw new ProposalException("Id do atendimento inválido!", 400);
        }

        $attendance = (new Attendance)->find($attendanceId);

        if(!$attendance){
            throw new ProposalException("Nenhum atendimento encontrado com esse id", 400);
        }

        $simulation = (new Simulation)->findByAttendanceId($attendanceId);

        if(!$simulation){
            throw new ProposalException("Nenhuma simulação encontrada para esse atendimento", 400);
        }

        $propostas = (new Proposal)->findByAttendanceId($attendanceId);

        if(!$propostas){
            throw new ProposalException("Nenhuma proposta encontrada para esse atendimento", 400);
        }

        return[
            "message" => "Proposta(s) encontrada(s) com sucesso",
            "data" => $propostas
        ];

    }
    
}