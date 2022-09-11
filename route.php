<?php

require __DIR__ . "/vendor/autoload.php";

ini_set('display_errors', 0);

/*
|--------------------------------------------------------------------------
| ROTAS DA API \ VERBO HTTP
|--------------------------------------------------------------------------
| Aqui ficam as rotas da aplicacao, que serao utilizadas para  a chamada dos 
| servicos.
|
*/

use Source\Router\Router;
use Source\Http\Middleware;

header('Content-Type: application/Json');

$router = new Router(getenv("BASE_PREFIX"));
$router->namespace("Source\Service");

/** Usuario **/
$router->group("user", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->post( "/authenticate", "AuthenticateService:authenticate", middleware: [ Middleware\Origin::class ]);
$router->post( "/", "InternalUserService:register");
$router->put( "/", "InternalUserService:update");
$router->get( "/", "InternalUserService:consultAll");
$router->get( "/{id}", "InternalUserService:consultById");
$router->get("/teste", "InternalUserService:teste");

/** Cliente */
$router->group("client", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->get("/{cpfOrHash}", "ClientService:consult");
$router->get('/fullData/{hash}', "ClientService:consultFullData");
$router->post('/', "ClientService:register");
$router->put('/{hashClient}', "ClientService:update");

/** Cliente Acesso */
$router->post('/access/{hashClient}', "ClientService:access");
$router->put('/access/{hashClient}', "ClientService:accessUpdate");

/** Proposta*/
$router->group("proposal", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->get("/{idAtendimento}", "ProposalService:consultByAttendance");
$router->post("/simulate/{hashClient}", "SimulationService:simulate");
$router->post("/simulate/save/{hashClient}", "SimulationService:register");

/** salva  proposta  */
$router->post("/save/", "TypingService:saveBankProposal");

/** Proposta digitaçao*/
$router->post("/bank/{hashClient}", "TypingService:proposalTyping");

/** Tabelas */
$router->group("table", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->get("/bank", "TableService:consultBank");

/** Atendimento */
$router->group("attendance", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->post("/{hashCliente}", "AttendanceService:register");
$router->get("/{hash}", "AttendanceService:consultByUserHash");

/** Canal */
$router->group("channel", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->get("/", "ChannelService:consultAll");
$router->get("/{id}", "ChannelService:consultById");

/** Empregador */
$router->group("employer", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->get("/convenant", "ConvenantService:consultAll");
$router->post("/convenant", "ConvenantService:register");
$router->get("/convenant/{idConvenant}", "ConvenantService:consultById");
$router->get("/department", "DepartmentService:consultAll");
$router->get("/department/{id}", "DepartmentService:consultById");
$router->post("/department", "DepartmentService:register");

/** Aceite LGPD */
$router->group("accept", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->get("/{hashCliente}", "LGPDService:consult");
$router->post("/{hashCliente}", "LGPDService:acceptRegister");

/** Tabulacao */
$router->group("tab", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->get("/", "TabService:consultAll");
$router->get("/{idTabulacao}", "TabService:consultById");
$router->put("/{hashCliente}", "TabService:setTab");

/**Simulação */
$router->group("simulation", [ Middleware\Authorization::class, Middleware\Permission::class, Middleware\Origin::class ]);
$router->get("/{idAtendimento}", "SimulationService:consultLastSimulation");

$router->group("report");
$router->get("/", "ReportService:consultAttendance");

/** Erros */
$router->group("erro");
$router->get("/404", "ErrorService:notFound");
$router->get("/403", "ErrorService:forbidden");
$router->get("/501", "ErrorService:notImplemented");
$router->dispatch();

/*** Erro de redirect */
if (!is_null($router->error())) {
    $router->redirect("/erro/{$router->error()}");
}
