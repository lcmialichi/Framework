<?php

namespace Source\Event\Listeners;

use Source\Event\Contracts\EventListenerInterface;
use Source\Event\Contracts\EventSubjectInterface;
use Source\Event\WebHook\SLA as WebHook;
use Source\Event\WebHook\Command\BashPrint as Bash;
use Source\Exception\WebHookException;

class SLAWebHook implements EventListenerInterface{

    public function update(EventSubjectInterface $sla) : void {
        
        if(!Bash::isInitialized()){
            Bash::initialize();
        }

        try{
            (new WebHook(
                idTab: $sla->idTabulacao,
                idProduct: $sla->idProduto,
                idStage: $sla->idEtapa,
                idAttendance: $sla->idAtendimento
            ))->submit();

            Bash::addSuccess();

        }catch(WebHookException $e){
            Bash::addFail();
        }
       
    }

}

