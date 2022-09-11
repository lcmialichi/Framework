<?php

namespace Source\Service;

use Source\Model\Accept;
use Source\Model\Client;
use Source\Router\Router;
use Source\Router\Request;
use Source\Model\ClientBank;
use Source\Model\ClientMail;
use Source\Model\ClientUser;
use Source\Http\Response\API;
use Source\Attributes\Response;
use Source\Model\ClientAddress;
use Source\Model\ClientContact;
use Source\Model\ClientDocument;
use Source\Model\ClientEmployer;
use Source\Attributes\Permission;
use Source\Exception\ClientException;
use Source\Service\ValueObject\AcceptObject;
use Source\Service\ValueObject\ClientObject;
use Source\Service\ValueObject\ClientBankObject;
use Source\Service\ValueObject\ClientMailObject;
use Source\Service\ValueObject\ClientUserObject;
use Source\Service\ValueObject\ClientAddressObject;
use Source\Service\ValueObject\ClientContactObject;
use Source\Service\ValueObject\ClientDocumentObject;
use Source\Service\ValueObject\ClientEmployerObject;

/*
|--------------------------------------------------------------------------
| CLIENT SERVICE
|--------------------------------------------------------------------------
| Aqui ficam as chamadas de todos os métodos referente ao cliente
| criação e atualização de dados do cliente (dados pessoais, endereço, 
| dados de acesso) o método diff só irá aceitar objeto específico do client 
| e irá retornar false caso tenha alguma mudança de dados
| todos os métodos (menos o register) são obrigatórios o envio e uso da 
| hash do cliente 
|
*/

#[Response(API::class)]
class ClientService
{

    public function __construct( private Router $router ) {}

    /**
     * Método para consultar dados dos clientes cadastradados por hash ou cpf
     */

    #[Permission(
        service: Permission::CLIENT,
        level: Permission::READ
    )]
    public function consult(Request $request) : array
    {
        /** Verifica o tipo de permissão do usuário */
       $permissionType = $request->middleware("Permission")["type"];
       $cpfOrHash = $request->inputs()["cpfOrHash"];

        /** Robo nao possui trava de atendimento */
       if($permissionType == Permission::ROBOT){   

            $cpfRegex = "/^[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}/";    
            if(strlen($cpfOrHash) != 32 && is_numeric($cpfOrHash)){
                if(preg_match($cpfRegex, $cpfOrHash)){
                    $client = (new Client)->findByCpf($cpfOrHash);

                    if($client){
                        unset($client->id);
                        return [
                            "message" => "Cliente encontrado por cpf!",
                             "dados" => [   
                                    "client" => $client
                                ]
                        ];
                    }
                }
            }

        $client =  (new Client)->findByHash($cpfOrHash);
        if($client){
            unset($client->id);
            return [
                "message" => "Cliente encontrado por hash!",
                 "dados" => [   
                        "client" => $client
                    ]
            ];
        }

            throw new \Exception("Nenhum cliente encontrado", 200);    
        }

        $this->router->redirect("/erro/501");

    }

    /**
     * Método para criar o usuário de acesso do cliente, apenas um por cliente.
     */

    #[Permission(
        service: Permission::CLIENT,
        level: Permission::CREATE
    )]
    public function access(Request $request) : array
    {
        #captura os dados enviados no body
        $inputs = $request->inputs();
        $dadosAcesso = $inputs["dadosAcesso"];

        #valida se foi enviado o array com os dados
        if(!$dadosAcesso)
        {
            throw new \Exception("Dados de acesso não fornecidos", 200);
        }

        $client = new Client;
        $clientUser = new ClientUser;

        #verifica e valida se existe o cliente cadastrado no sistema
        $result = $client->findByHash($inputs["hashClient"]);

        if(!$result)
        {
            throw new \Exception("Cliente não encontrado", 200);
        }

        #verifica se cliente já poossui cadastro cadastrado no sistema
        if($clientUser->getClient($result->id))
        {
            throw new \Exception("Cliente já possui usuário cadastrado", 200);
        }

        #verifica se o usuário enviado para cadastro já existe no sistema
        if($clientUser->verifyUser($dadosAcesso["usuario"]))
        {
            throw new ClientException("Dados de acesso inválido!", 200);
        }

        /**
         * Criação do objeto que será enviado para criação dos dados
         * feito dessa forma para manter a integridade dos dados
         */

        $clientUserObject = new ClientUserObject(
            idCliente : $result->id,
            usuario : $dadosAcesso["usuario"],
            senha : $dadosAcesso["senha"],
        );

        #criando o acesso do cliente
        $clientUser->register($clientUserObject); 

        return [
            "message" => "Acesso foi criado com sucesso!",
            "data" => "dadosAcesso"
        ];
    }

    /**
     * Método para atualizar o usuário de acesso do cliente
     */

     
    #[Permission(
        service: Permission::CLIENT,
        level: Permission::UPDATE
    )]
    public function accessUpdate(Request $request) : array
    {
        #captura os dados enviados no body
        $inputs = $request->inputs();
        $dadosAcesso = $inputs["dadosAcesso"];

        #valida se foi enviado o array com os dados
        if(!$dadosAcesso)
        {
            throw new \Exception("Dados de acesso não fornecidos", 200);
        }

        $client = new Client;
        $clientUser = new ClientUser;

        #array para mostrar apenas os dados que foram atualizados.
        $updatedData = [];

        #verifica e valida se existe o cliente cadastrado no sistema
        $result = $client->findByHash($inputs["hashClient"]);

        if(!$result)
        {
            throw new \Exception("Cliente não encontrado", 200);
        }

        #verifica se o usuário enviado para atualização existe no sistema
        if(!$clientUser->verifyUser($dadosAcesso["usuario"]))
        {
            throw new ClientException("Dados de acesso inválido!", 200);
        }

        #captura os dados do cliente dentro do sistema para comparação
        $resultClient = $client->getClient($inputs["hashClient"]);
        
        $clientUserObject = new ClientUserObject(
            idCliente : $result->id,
            usuario : $dadosAcesso["usuario"],
            senha : $dadosAcesso["senha"],
        );

        #se encontrar um acesso com o usuário informado, porém de outro cliente
        $resultUsuario = $clientUser->getClient($resultClient->getId());

        if(!$resultUsuario)
        {
            throw new \Exception("Usuário está cadastrado em outro cliente", 200);
        }

        # atualiza a senha do usuário
        if(!$clientUserObject->diff($resultUsuario))
        {
            $clientUser->updateModel(
               data: [
                "acesso_senha" => $clientUserObject->getPassword(),
            ],
               id: $resultClient->getId()
            );

            $updatedData[] = "dadosAcesso";
        }

        #se nada for atualizado
        if(!$updatedData)
        {
            throw new \Exception("Nenhuma informação foi atualizada", 200);
        }

        return [
            "message" => "Acesso foi atualizado com sucesso!",
            "data" => $updatedData
        ];
    }

    /**
     * Método para cadastrar o cliente dentro do sistema, cpf obrigatório
     */

    #[Permission(
        service: Permission::CLIENT,
        level: Permission::CREATE
    )]
    public function register(Request $request) : array
    {
        #captura os dados enviados no body
        $inputs = $request->inputs();
        $dadosPessoais = $inputs["dadosPessoais"];

        #se não for enviado o array dadosPessoais, retorna um erro
        if(is_null($dadosPessoais))
        {
            throw ClientException::invalidField("dadosPessoais");
        }

        $client = new Client;

        #se cpf informado já estiver cadastrado no sistema, retorna um erro
        if($client->findByCpf($dadosPessoais["cpf"])){
            throw new ClientException("Cliente já cadastrado", 200);
        }

        #a hash é criada no modelo md5 a partir do cpf informado
        $hash = md5($dadosPessoais["cpf"]);

        /**
         * @todo Isso aqui esta muito feio, pêra ira arrumar depois
         */
         if(!is_null($dadosPessoais['dataNascimento'])){ #Ajusta Formatacao da data de nascimento
            if(\DateTime::createFromFormat("d/m/Y", $dadosPessoais['dataNascimento'])){
                $convertDate = str_replace("/", "-", $dadosPessoais["dataNascimento"]);
                $dadosPessoais['dataNascimento'] = date('Y-m-d', strtotime($convertDate));
            }
         }
         
        /**
         * Criação do objeto que será enviado para criação cliente
         * feito dessa forma para manter a integridade dos dados
         */
        $clientObject = new ClientObject(
            nome: strtoupper($dadosPessoais["nome"]),
            cpf: $dadosPessoais["cpf"],
            sexo: strtoupper($dadosPessoais["sexo"]),
            estadoCivil: strtoupper($dadosPessoais["estadoCivil"]),
            nacionalidade: strtoupper($dadosPessoais["nacionalidade"]),
            nomeMae: strtoupper($dadosPessoais["nomeMae"]),
            dataNascimento: $dadosPessoais["dataNascimento"],
            hash: $hash
        );

        #cadastra o cliente
        $client->register($clientObject);
     
        return [
            "message" => "Cliente cadastrado com sucesso!",
            "data" => [
                "hash" => $clientObject->getHash()
            ]
        ];
    }

    /**
     * Método para alterar os dados cliente dentro do sistema, hash obrigatório
     */

    #[Permission(
        service: Permission::CLIENT,
        level: Permission::UPDATE
    )]
    public function update (Request $request)
    {

        $client = new Client;
        $clientAddress = new ClientAddress;
        $clientBank = new ClientBank;
        $clientContact = new ClientContact;
        $clientMail = new ClientMail;
        $clientDocument = new ClientDocument;
        $clientEmployer = new ClientEmployer;
        $inputs = $request->inputs();

        #se for enviado apenas a hash do cliente, sem nenhum dado no body
        if((count($inputs)) === 1)
        { 
            throw new  ClientException(" Nenhum campo foi enviado para atualização", 200);
        }

        #captura os dados enviado no body da requisição
        $dadosPessoais = $inputs["dadosPessoais"];
        $dadosEndereco = $inputs["dadosEndereco"];
        $dadosBancarios = $inputs["dadosBancarios"];
        $dadosContato = $inputs["dadosContato"];
        $dadosEmail = $inputs["dadosEmail"];
        $dadosDocumentos = $inputs["dadosDocumentos"];
        $dadosEmpregador = $inputs["dadosEmpregador"];
        $updatedData = [];

        $result = $client->getClient($inputs["hashClient"]);

        if(!$result)
        {
            throw new ClientException("Hash informada nao esta atrelada a nenhum cliente!", 200);
        }

        /**
         * Validação dos dados, caso não exista irá atualizar os outros dados que foram enviados
         * não necessitando o envio de todos os dados
         */
     
        if(!is_null($dadosPessoais))
        {
            $resultPessoais = $result;

        }
        if(!is_null($dadosEndereco))
        {
            $resultEndereco = $clientAddress->getClient($result->getId());
        }
        if(!is_null($dadosBancarios))
        {
            $resultBanco = $clientBank->getClient($result->getId());
        }

        if(!is_null($dadosContato))
        {
            $resultContato = $clientContact->getClient($result->getId());
        }

        if(!is_null($dadosEmail))
        {
            $resultEmail = $clientMail->getClient($result->getId());
        }

        if(!is_null($dadosDocumentos))
        {
            $resultDocumentos = $clientDocument->getClient($result->getId());
        }

        if(!is_null($dadosEmpregador))
        {
            $resultEmpregador = $clientEmployer->getClient($result->getId());
        }

        /**
         * Criação dos objetos que serão enviados para atualização de dados
         * feito dessa forma para manter a integridade dos dados
         */
        $clientObject = new ClientObject(
            nome: strtoupper($dadosPessoais["nome"]),
            sexo: strtoupper($dadosPessoais["sexo"]),
            estadoCivil: strtoupper($dadosPessoais["estadoCivil"]),
            nacionalidade: strtoupper($dadosPessoais["nacionalidade"]),
            nomeMae: strtoupper($dadosPessoais["nomeMae"]),
            dataNascimento: $dadosPessoais["dataNascimento"],
        );

        $clientAddressObject = new ClientAddressObject(
            idCliente: $result->getId(),
            uf: strtoupper($dadosEndereco["uf"]),
            cidade: strtoupper($dadosEndereco["cidade"]),
            bairro: strtoupper($dadosEndereco["bairro"]),
            rua: strtoupper($dadosEndereco["rua"]),
            numero: $dadosEndereco["numero"],
            cep: $dadosEndereco["cep"],
            complemento: strtoupper($dadosEndereco["complemento"]),
        );

        $clientBankObject = new ClientBankObject(
            idCliente: $result->getId(),
            numeroBanco: $dadosBancarios["numeroBanco"],
            agencia: $dadosBancarios["agencia"],
            conta: $dadosBancarios["conta"],
            digitoConta: $dadosBancarios["digitoConta"],
            tipoConta: strtoupper($dadosBancarios["tipoConta"]),
            bancoAverbacao: $dadosBancarios["bancoAverbacao"],
        );

        $clientContactObject = new ClientContactObject(
            idCliente: $result->getId(),
            ddd: $dadosContato["ddd"],
            telefone: $dadosContato["telefone"],
        );

        $clientMailObject = new ClientMailObject(
            idCliente: $result->getId(),
            email: $dadosEmail["email"],
        );

        $clientDocumentObject = new ClientDocumentObject(
            idCliente : $result->getId(),
            documento : $dadosDocumentos["documento"],
            idTipo : strtoupper($dadosDocumentos["idTipo"])
        );

        $clientEmployerObject = new ClientEmployerObject(
            idCliente : $result->getId(),
            especie : $dadosEmpregador["especie"],
            uf : strtoupper($dadosEmpregador["uf"]),
            matricula : $dadosEmpregador["matricula"]
        );

        /**
         * Validação para ver se houve mudança dos dados enviados pelo usuário com os dados cadastrados no sistema
         * caso nada mude, será retornado uma mensagem dizendo que não houve alteração dos dados
         */

        if(isset($dadosPessoais))
        {
            $data = [];
            !$dadosPessoais["nome"] ?: $data["cliente_nome"] = $clientObject->getNome();
            !$dadosPessoais["sexo"] ?: $data["cliente_sexo"] = $clientObject->getSexo();
            !$dadosPessoais["estadoCivil"] ?: $data["cliente_estado_civil"] = $clientObject->getEstadoCivil();
            !$dadosPessoais["nacionalidade"] ?: $data["cliente_nacionalidade"] = $clientObject->getNacionalidade();
            !$dadosPessoais["nomeMae"] ?: $data["cliente_nome_mae"] = $clientObject->getNomeMae();
            !$dadosPessoais["dataNascimento"] ?: $data["cliente_data_nascimento"] = $clientObject->getDataNascimento();

            $client->updateModel(
               data: $data,
               id: $result->getId()
            );

           $updatedData[] = "dadosPessoais";
        }

        if((!$resultEndereco || !$clientAddressObject->diff($resultEndereco)) && $dadosEndereco)
        {
            $clientAddress->register($clientAddressObject);
            $updatedData[] = "dadosEndereco";
        }
        if((!$resultBanco || !$clientBankObject->diff($resultBanco)) && $dadosBancarios)
        {
            $clientBank->register($clientBankObject);
            $updatedData[] = "dadosBancarios";
        }
        if((!$resultContato || !$clientContactObject->diff($resultContato)) && $dadosContato)
        {
            $clientContact->register($clientContactObject);
            $updatedData[] = "dadosContato";
        }
        if((!$resultEmail || !$clientMailObject->diff($resultEmail)) && $dadosEmail)
        {
            $clientMail->register($clientMailObject);
            $updatedData[] = "dadosEmail";
        }
        if((!$resultDocumentos || !$clientDocumentObject->diff($resultDocumentos)) && $dadosDocumentos)
        {
            $clientDocument->register($clientDocumentObject);
            $updatedData[] = "dadosDocumentos";
        }
        if((!$resultEmpregador || !$clientEmployerObject->diff($resultEmpregador)) && $dadosEmpregador)
        {
            $clientEmployer->register($clientEmployerObject);
            $updatedData[] = "dadosEmpregador";
        }
        
        /** Nada foi atualizado */
        if(empty($updatedData))
        {
          return [
                "message" => "Nenhuma informação foi atualizada",
                "data" => []
            ];
        }

        return [
            "message" => "Cliente atualizado com sucesso!",
            "data" => [
                "dadosAtualizados" => implode( ", ", $updatedData )
            ]
        ];

    }

    public function consultFullData(Request $request){

        $hash = $request->inputs()["hash"];

        /**Procura o cliente por hash */
        $client = (new Client)->findByHash($hash);

        /**Se não achou o cliente */
        if(!$client)
        {
            throw new ClientException("Cliente não encontrado", 400);
        }

        /**Forma o retorno inicial */
        $retorno = [
            "message" => "Cliente encontrado",
            "data" => []
        ];

        $retorno["data"]["dadosPessoais"] = $client;

        /**Procura os endereços do cliente por Id */
        $clientAddress = (new ClientAddress)->findByClientId($client->id);

        /**Se achou endereços  */
        if($clientAddress){
            
            $retorno["data"]["dadosEndereco"] = end($clientAddress); //somente o ultimo

        }

        /**Procura os dados bancarios do cliente por Id */
        $clientBank = (new ClientBank)->findByClientId($client->id);

        /**Se achou dados bancarios */
        if($clientBank){
            $retorno["data"]["dadosBancarios"] = end($clientBank); //somente o ultimo
        }

        /**Procura os contatos do cliente por Id */
        $clientContact = (new ClientContact)->findByClientId($client->id);

        /**Se achou contatos do cliente */
        if($clientContact){
            $retorno["data"]["dadosContato"] = end($clientContact); //somente o ultimo
        }

        if(array_keys($retorno["data"]) != ["dadosPessoais", "dadosEndereco", "dadosBancarios", "dadosContato"]){
            throw new ClientException("cliente possui dados incompletos", 200);
        }

        return $retorno;

    }
}