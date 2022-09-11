<?php

namespace Source\Service; // Definição do namespace

use Source\Model\Department; // Será responsável por fazer a gestão de conexão com o BD
use Source\Router\Request; //Define o tipo Request que será utilizado na entrada do Controller
use Source\Http\Response\API; //Controle de resposta da saída de todos os dados retornados
use Source\Attributes\Response; // Atributo de resposta utilizado para definir o tipo de resposta
use Source\Attributes\Permission; // Define o permissionamento de cada serviço
use Source\Exception\EmployerException; // Define as tratativas de erros
use Source\Service\ValueObject\DepartmentObject; // Responsável por fazer a gestão de validação dos dados
use Source\Model\Convenant;

#[Response(API::class)] // Define um atributo para Classe DepartmentService com Response (Classe responsável por formatar a saída de dados)
class DepartmentService 
{

    #[Permission(
        service: Permission::EMPLOYER,
        level: Permission::READ
    )]
    public function consultAll()
    {

        $department = new Department(); // Cria instância da classe Department (Model)
        $listDepartments = $department->all(useAlias: true); // Busca todos os departamentos no banco de dados e ativa a troca de nomenclatura "alias"

        if (!$listDepartments) { // Verifica se a lista de departamentos está vazia
            throw new EmployerException("Não foi possível listar os orgãos", 400); // Lança uma exceção caso não seja possível listar os departamentos
        }

        return [ // Constroi um array com o retorno caso passe pela validação
            "message" => "Lista gerada com sucesso.",
            "data" => $listDepartments // Variável carregada anteriormente com o retorno da função all()
        ];
    }

    #[Permission(
        service: Permission::EMPLOYER,
        level: Permission::READ
    )]
    public function consultById(Request $request) // Cria função para consultar um departamento pelo seu id
    {
        $inputs = $request->inputs(); // Recebe os dados da requisição
        $department = new Department(); // Cria instância da classe Department (Model)

        if(!is_numeric($inputs['id'])){
            throw new EmployerException("id possui valor invalido", 400);

        }
        $departmentsData = $department->find($inputs['id'], useAlias: true); // Busca o departamento pelo id e ativa a troca de nomenclatura "alias"

        if (!$departmentsData) { // Verifica se o departamento não foi encontrado
            throw new EmployerException("Não foi possível encontrar o departamento", 400); // Lança uma exceção caso não seja possível encontrar o departamento
        }
        return [ // Constroi um array com o retorno caso passe pela validação
            "message" => "Departamento encontrado com sucesso.",
            "data" => $departmentsData // Variável carregada anteriormente com o retorno da função find()
        ];
    }

    #[Permission(
        service: Permission::EMPLOYER,
        level: Permission::READ
    )]
    public function register(Request $request)
    {

        $inputs = $request->inputs();
        $departmentObject = new DepartmentObject(descricao: $inputs['descricaoOrgao']);

        $department = new Department();
        if ($department->findByName(strtoupper($departmentObject->getDescricao()))) {
            throw new EmployerException("Orgão já cadastrado", 400);
        }

        if(!is_numeric($inputs['idConvenio'])){
            throw new EmployerException("idConvenio possui valor invalido", 400);

        }

        $convenant = new Convenant();
        if (!$convenant->find($inputs['idConvenio'])) {
            throw new EmployerException("Convênio não encontrado", 400);
        };

        $departamentId = $department->register($departmentObject);

        $convenant->registerEmployer(
            idConvenant: $inputs['idConvenio'],
            idDepartment: $departamentId
        );

        return [ // constroi um array com o retorno caso seja true
            "message" => "Orgão cadastrado com sucesso.",
            "data" => [
                "idDepartamento" => $departamentId,
                "idEmpregador" => $inputs['idConvenio']
            ]
        ];
    }
}
