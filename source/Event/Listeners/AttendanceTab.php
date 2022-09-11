<?php

namespace Source\Event\Listeners;

use Source\Model\Attendance;
use Source\Event\Contracts\EventSubjectInterface;
use Source\Event\Contracts\EventListenerInterface;

class AttendanceTab implements EventListenerInterface
{

    const PAGO = "PAGO";
    const REPROVADO = "REPROVADO";

    /**
     * @param Source\Event\EventListenerInterface $proposal
     * @return void
     */
    public function update( EventSubjectInterface  $proposal): void
    {   
        $activity = $proposal->activity;
        if( $activity == self::PAGO || $activity == self::REPROVADO){
            (new Attendance)->updateModel(
                data: [
                    "atendimento_status" => 2,
                    "atendimento_finalizacao" => 1,
                    "atendimento_data_finalizacao" => date("Y-m-d H:i:s"),
                    "atendimento_id_tabulacao" =>  $activity == self::PAGO ? 20 : 27
                ],
                idAtendimento: $proposal->idAtendimento
            );
        }
        
    }

}