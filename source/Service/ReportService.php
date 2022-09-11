<?php 

namespace Source\Service;

use Source\Attributes\Response;
use Source\Http\Response\API;
use Source\Model\Attendance;
use Source\Router\Request;
use Source\Router\Router;
use \ClanCats\Hydrahon\Query\Expression as raw;

#[Response(API::class)]
class ReportService{


    function __construct(private Router $router) {}

    function consultAttendance(Request $request){

        $inputs = $request->inputs();

        var_dump($request);exit;

        $items_per_page = $inputs["items_per_page"]; 
        $page = $inputs["page"];
        $offset = ($page - 1) * $items_per_page;

        $attendanceModel = new Attendance();

        $attendances = $attendanceModel->select(
            get:[
                "cliente_id",
                "cliente_nome",
                "cliente_cpf",
                "usuario_nome",
                "origem_descricao",
                "produto_nome",
                "etapa_descricao",
                "tabulacao_descricao",
                new raw("
                    case 
                    when atendimento_status = 1 then 'Em progresso'
                    else 'Encerrado'
                    end as atendimento_status
                "),
                "atendimento_data_finalizacao",
                "atendimento_data_insert"
        
            ]
        )
        ->join("cliente as t2", "atendimento.atendimento_id_cliente", "=", "t2.cliente_id")
        ->join("usuario as t3", "atendimento.atendimento_id_usuario", "=", "t3.usuario_id")
        ->join("origem as t4", "atendimento.atendimento_id_origem", "=", "t4.origem_id")
        ->join("produto as t5", "atendimento.atendimento_id_produto", "=", "t5.produto_id")
        ->join("atendimento_etapa as t6", "atendimento.atendimento_id_etapa", "=", "t6.etapa_id")
        ->join("atendimento_tabulacao as t7", "atendimento.atendimento_id_tabulacao", "=", "t7.tabulacao_id")
        ->execute();

        return [
            "message" => "Relatório gerado com sucesso",
            "data" => $attendances,
            "count" => count($attendances)
        ];
    }

}