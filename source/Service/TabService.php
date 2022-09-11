<?php

namespace Source\Service;

use Source\Model\Tab;
use Source\Model\Client;
use Source\Model\TabLog;
use Source\Router\Router;
use Source\Router\Request;
use Source\Model\Attendance;
use Source\Http\Response\API;
use Source\Log\AttendanceLog;
use Source\Exception\TabError;
use Source\Attributes\Response;
use Source\Attributes\Permission;
use Source\Exception\TabException;
use Source\Service\ValueObject\TabLogObject;
use Source\Domain\Token;
use Source\Enum\AttendanceLogAction as Action;

#[Response(API::class)]
class TabService
{

  public function __construct(
    private Router $router
  ) {
  }

  #[Permission(
    service: Permission::TAB,
    level: Permission::UPDATE
  )]
  public function setTab(Request $request)
  {

    $inputs = $request->inputs();

    if (is_null($inputs['idTabulacao'])) {
      throw new TabException("O id da tabulacao não pode ser nulo");
      
    }
    if (!is_numeric($inputs["idAtendimento"])) {
      throw tabException::invalidField("idAtendimento");

    }

    $hashClient = $inputs["hashCliente"];
    //valida se a hash tem o formato valido
    if (!preg_match("/^[0-9a-f]{32}$/", $hashClient)) {
      throw new TabException("Hash do cliente inválido", 400);

    }

    $client = (new Client)->getClient($hashClient);
    if (!$client) {
      throw new TabException("Cliente não encontrado", 400);

    }

    $attendance = new Attendance;
    $attendanceInProcess = $attendance->find(id: $inputs["idAtendimento"], useAlias: true);

    if (
      !$attendanceInProcess ||
      $attendanceInProcess->idUsuario != Token::getUserId() ||
      $attendanceInProcess->status != Attendance::ATTENDANCE_IN_PROCESS
    ) {
      throw new TabException("Usuario nao possui atendimento com cliente informado", 400);

    }
    
    if (is_null($attendanceInProcess->idProduto)) {
      throw new TabException("Informe um produto para tabular atendimento", 400);

    }
     
    $tab = new tab;
    $config = $tab->findByConfig(
      idTab: $inputs['idTabulacao'],
      idProduct: $attendanceInProcess->idProduto
    );

    if (!$config) {
      throw new TabException("Configuraçao de tabulacao informada inexistente");
    }

    /**
     * Atualiza o atendimento com a configuração de tabulação
     * Caso a tublacao seja de encerramento. finaliza atendimento.
     */
    $update = $attendance->updateModel(
      data: [
        ...[
          "atendimento_id_tabulacao" => $config->idTabulacao,
          "atendimento_id_etapa" => $config->idEtapa,
          "atendimento_data_tabulacao" => date("Y-m-d H:i:s"),
          "atendimento_expirou_tab" => 0
        ],
        ...$config->finaliza ? [
          "atendimento_finalizacao" => $config->finaliza,
          "atendimento_data_finalizacao" => date("Y-m-d H:i:s"),
          "atendimento_status" => Attendance::ATTENDANCE_CLOSED,
        ] : []
      ], # Se finalizacao for true, adiciona data finalizacao e status de encerrado pelo corretor
      idAtendimento: $attendanceInProcess->idAtendimento
    );

    if ($config->finaliza) {
      #Log de atendimento
      AttendanceLog::save(
        acao: Action::UPDATED_TO_CLOSED,
        idAtendimento: $attendanceInProcess->idAtendimento
      );
    }

    if (!$update) {
      throw new TabError("Ocorreu um erro ao atualizar tabulacao");
    }

    /** LOG de Tabulacao */
    $logTab = new TabLog;
    $logTab->register(new TabLogObject(
      idAtendimento: $attendanceInProcess->idAtendimento,
      idEtapa: $config->idEtapa,
      idProduto: $config->idProduto,
      idTabulacao: $config->idTabulacao
    ));

    return [
      "message" => "Tabulacao atualizada com sucesso",
      "data" => [
        "idEtapa" => $config->idEtapa,
        "nomeEtapa" => $config->etapaDescricao
      ]
    ];
  }

  #[Permission(
    service: Permission::TAB,
    level: Permission::READ
  )]
  public function consultAll()
  {

    $tab = new tab;
    $tabs = $tab->all(useAlias: true);
    if (!$tabs) {
      throw new TabException("Nenhuma tabulacao cadastrada");
    }

    return [
      "message" => "lista de tabulacoes gerada com sucesso!",
      "data" => $tabs
    ];
  }

  #[Permission(
    service: Permission::TAB,
    level: Permission::READ
  )]
  public function consultById(Request $request)
  {

    $id = $request->inputs()["idTabulacao"];
    if (!is_numeric($id)) {
      throw new TabException("Id da tabulacao invalido");
    }

    $tab = new tab;
    $tabs = $tab->find(id: $id, useAlias: true);

    if (!$tabs) {
      throw new TabException("Nenhuma tabulacao cadastrada");
    }

    return [
      "message" => "tabulacao encontrada com sucesso!",
      "data" => $tabs
    ];
  }
}
