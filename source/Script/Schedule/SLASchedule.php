<?php

use Source\Event\Listeners\SLAWebHook;

require_once __DIR__ . "/../../../vendor/autoload.php";

/*
|--------------------------------------------------------------------------
| ROTINA DE SLA
|--------------------------------------------------------------------------
| A rotina atualiza a cada 1 segundo todos as tabulaçoes que tiverem
| seu tempo de vida encerrado (envia webhook)
|
*/

$sla = new Source\Event\SLAEvent; 
$sla->attach(new SLAWebHook);
$sla->handle();