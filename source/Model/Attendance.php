<?php

namespace Source\Model;

use Source\Model\ORM\Column;
use Source\Model\ORM\Entity;
use \Source\Model\ORM\Model as Schema;
use Source\Service\ValueObject\AttendanceObject;
use Source\Domain\Origin;

#[Entity("atendimento")]
class Attendance extends Schema
{

    const ATTENDANCE_INACTIVE = 0;
    const ATTENDANCE_IN_PROCESS = 1;
    const ATTENDANCE_CLOSED = 2;

    #[Column(alias: "idAtendimento", key: Column::PK)]
    private $atendimento_id;

    #[Column(alias: "idCliente")]
    private $atendimento_id_cliente;

    #[Column(alias: "idUsuario")]
    private $atendimento_id_usuario;

    #[Column(alias: "status")]
    private $atendimento_status;

    #[Column(alias: "idCanal")]
    private $atendimento_id_origem;

    #[Column(alias: "idFinalizacao")]
    private $atendimento_finalizacao;

    #[Column(alias: "dataFinalizacao")]
    private $atendimento_data_finalizacao;

    #[Column(alias: "idTabulacao")] #FK Configuração de tabulação
    private $atendimento_id_tabulacao;

    #[Column(alias: "idProduto")]  #FK Configuração de tabulação
    private $atendimento_id_produto;

    #[Column(alias: "idEtapa")]  #FK Configuração de tabulação
    private $atendimento_id_etapa;

    #[Column(alias: "dataCadastro", generatedValue: true)]
    private $atendimento_data_insert;

    #[Column(alias: "dataAtualizacao", generatedValue: true)]
    private $atendimento_data_update;

    #[Column(alias: "idAtendimentoAnterior")]
    private $atendimento_id_atendimento_anterior;

    #[Column(alias: "dataAtendimento")]
    private $atendimento_data_tabulacao;

    #[Column(alias: "ExpirouAtendimento")]
    private $atendimento_expirou_tab; 
    /**
     * Busca os atendimentos pela hash do cliente
     * 
     * @param string $hash
     * @return array|bool
     */
    public function findByClientHash(string $hash): array|bool
    {

        return Schema::select(
            get: [
                "atendimento_id" => "idAtendimento",
                "atendimento_id_cliente" => "idCliente",
                "atendimento_id_produto" => "idProduto",
                "atendimento_id_etapa" => "idEtapa",
                "atendimento_id_tabulacao" => "idTabulacao",
                "atendimento_status" => "status",
                "cliente_nome" => "clienteNome",
                "atendimento_data_insert" => "dataCadastro",
            ]
        )
            ->join("cliente as t2", "atendimento.atendimento_id_cliente", "=", "t2.cliente_id")
            ->where("t2.cliente_hash", "=", $hash)
            ->execute();
    }

    /**
     * Cria um atendimento entre o usuario e o cliente
     * @param AttendanceObject $attendanceObject
     * @return bool
     */
    public function register(AttendanceObject $attendanceObject): int|bool
    {
        $this->atendimento_id_cliente = $attendanceObject->getIdCliente();
        $this->atendimento_id_usuario = $attendanceObject->getIdUsuario();
        $this->atendimento_id_produto = $attendanceObject->getIdProduto();
        $this->atendimento_id_origem = Origin::getOrigin();
        $this->atendimento_status = $attendanceObject->getStatus();
        $this->atendimento_id_atendimento_anterior = $attendanceObject->getIdAtendimentoAnterior(isNullable: true);
        return $this->save();
    }

    /**
     * Atualiza a tabulacao do atendimento
     * 
     * @param AttendanceObject $attendanceObject
     * @return array|bool
     */
    public function updateTab(AttendanceObject $attendanceObject)
    {

        $tabulacao = $attendanceObject->getIdTabulacao();
        return $this->update(["atendimento_id_tabulacao" => $tabulacao])
            ->where("atendimento_id", "=", $attendanceObject->getIdAtendimento())
            ->execute();
    }

    public function findByUserAndClient(int $idUsuario, int $idCliente): array|bool
    {
        return Schema::select(
            get: [
                "atendimento_id" => "idAtendimento",
                "atendimento_id_cliente" => "idCliente",
                "atendimento_status" => "status",
                "cliente_nome" => "clienteNome",
                "atendimento_data_insert" => "dataCadastro",
                "usuario_is_robo" => "usuarioIsRobo",
                "atendimento_id_tabulacao" => "idTabulacao",
                "atendimento_id_produto" => "idProduto",
                "atendimento_id_etapa" => "idEtapa"
            ]
        )
            ->join("cliente as t2", "atendimento.atendimento_id_cliente", "=", "t2.cliente_id")
            ->join("usuario as t3", "atendimento.atendimento_id_usuario", "=", "t3.usuario_id")
            ->where("atendimento_id_usuario", "=", $idUsuario)
            ->where("atendimento_id_cliente", "=", $idCliente)
            ->execute();
    }

    /**
     * @param string[] $data
     * @param int $idUsuario
     */
    public function updateModel(array $data, int $idAtendimento): bool
    {
        return Schema::update($data)->where("atendimento_id", "=", $idAtendimento)->execute();
    }

    /**
     * Procura atendimento ativo por produto, usuario, e cliente
     *
     * @param integer $idUser
     * @param integer $idProduct
     * @param integer $idClient
     * @return object|false
     */
    public function findByProduct(int $idUser, int $idProduct, int $idClient, int $status = 1): object|false
    {
        return Schema::select(useAlias: true)
            ->where("atendimento_id_produto", $idProduct)
            ->where("atendimento_id_cliente", $idClient)
            ->where("atendimento_id_usuario", $idUser)
            ->where("atendimento_status", $status)
            ->last("atendimento_id");
    }

    public function findAllByConfig( int $expirou = 0)
    {
       return  Schema::select([
            "atendimento_id" => "idAtendimento",
            "atendimento_id_produto" => "idProduto",
            "atendimento_id_etapa" => "idEtapa",
            "atendimento_id_tabulacao" => "idTabulacao",
            "tabulacao_tempo_limite" => "tempoLimite",
            "atendimento_data_tabulacao" => "dataTabulacao",
        ])
            ->join("produto_tabulacao as t1", function ($join) {
                $join->on("t1.tabulacao_id_tabulacao", "=", "atendimento_id_tabulacao");
                $join->on("t1.tabulacao_id_produto", "=", "atendimento_id_produto");
                $join->on("t1.tabulacao_id_etapa", "=", "atendimento_id_etapa");
            })
            ->whereNull("atendimento_finalizacao")
            ->where("atendimento_expirou_tab", "!=", 1)
            ->get();
    }
}
