<?php

use Source\Event\Listeners\AttendanceTab;
use Source\Event\Listeners\ProposalWebhook;

require_once __DIR__ . "/../../../vendor/autoload.php";

/*
|--------------------------------------------------------------------------
| ROTINA DE CONSULTA STATUS DE PROPOSTA
|--------------------------------------------------------------------------
| A rotina atualiza status a cada 1 minuto, dispara eventos para webhook,
| atualizaçao de tabulaçao em atendimento.
|
*/

$event = new  Source\Event\ProposalEvent;
$event->attach(new ProposalWebhook);
$event->attach(new AttendanceTab);
$event->handle();
