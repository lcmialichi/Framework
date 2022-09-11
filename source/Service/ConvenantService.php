<?php

namespace Source\Service; // Definição do namespace

use Source\Model\Convenant; // Será responsável por fazer a gestão de conexão com o BD
use Source\Router\Request; //Define o tipo Request que será utilizado na entrada do Controller
use Source\Http\Response\API; //Controle de resposta da saída de todos os dados retornados
use Source\Attributes\Response; // Atributo de resposta utilizado para definir o tipo de resposta
use Source\Attributes\Permission; // Define o permissionamento de cada serviço
use Source\Exception\EmployerException; // Define as tratativas de erros
use Source\Service\ValueObject\ConvenantObject; // Responsável por fazer a gestão de validação dos dados

#[Response(API::class)] // Define um atributo para Classe ConvenantService com Response (Classe responsável por formatar a saída de dados)
class ConvenantService // Cria a Classe onde iremos gerar as funções 
{
    #[Permission( // Define o permissionamento para o serviço
        service: Permission::EMPLOYER, // Define qual serviço especifico de permissão será atribuido nesta função
        level: Permission::READ // Define o level que é necessário para acessar o serviço
    )]
    public function consultAll() //Cria função que irá exibir todos os convênios e que não recebe parametros
    {

        $convenant = new Convenant(); // Cria instância da classe Convenant (Model)
        $listConvenants = $convenant->all(useAlias: true); // Busca todos os convênios no banco de dados e ativa a troca de nomenclatura "alias"

        if (!$listConvenants) { // Valida se a função all() retornou false
            throw new EmployerException("Não foi possível listar os convênios", 400); // Lança a exceção para o Controller
        }

        return [ // Constroi um array com o retorno caso passe pela validação
            "message" => "Lista gerada com sucesso.",
            "data" => $listConvenants // Variável carregada anteriormente com o retorno da função all()
        ];
    }

    #[Permission( // Define o permissionamento para o serviço
        service: Permission::EMPLOYER, // Define qual serviço especifico de permissão será atribuido nesta função
        level: Permission::READ // Define o level que é necessário para acessar o serviço
    )]
    public function consultById(Request $request) //Cria função que irá exibir o convênio correspondente ao ID enviado
    {

        $inputs = $request->inputs(); // Retorna um Array com tudo que foi enviado na requisição
        $convenant = new Convenant(); // Cria instância da classe Convenant (Model)
        if(!is_numeric($inputs["idConvenant"])){
            throw new EmployerException("Não foi possível localizar o convênio", 400); // Lança a exceção para o Controller
        }

        $convenantsData = $convenant->find($inputs["idConvenant"], useAlias: true); // Busca o convênio no banco de dados pelo ID

        if (!$convenantsData) { // Valida se a função find() retornou false
            throw new EmployerException("Não foi possível localizar o convênio", 400); // Lança a exceção para o Controller
        }
        return [ // constroi um array com o retorno caso seja true
            "message" => "Consulta gerada com sucesso.",
            "data" => $convenantsData // find() retorna os dados que pertencerem ao ID (primary key) que foi enviado
        ];
    }

    #[Permission( // Define o permissionamento para o serviço
        service: Permission::EMPLOYER, // Define qual serviço especifico de permissão será atribuido nesta função
        level: Permission::CREATE // Define o level que é necessário para acessar o serviço
    )]
    public function register(Request $request) // Função responsável por enviar os Objetos já validados para a Inserção no Banco de Dados
    {

        $inputs = $request->inputs(); // Retorna um Array com tudo que foi enviado na requisição
        $convenantObject = new ConvenantObject( // Cria instância da classe ConvenantObject 
            nome: $inputs["nome"],
            codWebcred: $inputs["codWebcred"],
            codApiGateway: $inputs["codApiGateway"],
            cnpj: $inputs["cnpj"]
        ); // carregam os parâmetros que foram enviados na requisição

        $convenant = new Convenant(); // Cria instância da classe Convenant
        if ($convenant->findByName(strtoupper($convenantObject->getNome()))) { // Valida se já existe um convênio com o nome enviado
            throw new EmployerException("Já existe um convênio com este nome", 400); // Lança a exceção para o Controller

        }

        $data = $convenant->register($convenantObject); // Aciona a função resgister (que faz a inserção no banco de dados) passando os parâmetros do objeto criado anteriormente
        return [ // constroi um array com o retorno caso seja true
            "message" => "Convênio cadastrado com sucesso.",
            "data" => ["id" => $data] // Retorna o ID do convênio cadastrado
        ];
    }
}
