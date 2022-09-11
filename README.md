# **Framework para desenvolvimento de APIs**
  - Rotas: [Routes\Coffecode](https://github.com/robsonvleite/router) (modificado)
  - Gerenciamento de Logs : [Seldaek/monolog](https://github.com/Seldaek/monolog)
  -  JWT: [firebase/php-jwt](https://github.com/firebase/php-jwt)
 - QueryBuilder: [ClanCats/Hydrahon](https://github.com/ClanCats/Hydrahon)

# **Quick Start:**

O sistema foi feito para ser generico na construçao das comunicaçoes entre os objetos.
Tente manter sempre as boas praticas.

## **Setup:**
---
- Apos baixar o repositorio, para baixar as dependencias execute o seguinte comando na raiz do projeto:
```console 
meu@pa:~s composer update
 ```
 - Para baixar as dependencias de dev execute o comando:
```console 
meu@pa:~s composer update --dev
 ```
 - **(LINUX)** Na raiz do projeto execute o seguinte comando para gerar o arquivo .env:
 ```console 
meu@pa:~s cp ./example/.envExample ./.env
 ```

 Para finalizar Apenas substitua as variaveis de ambiente com valores reais (Conexao com o banco, chave JWT, configuraçao do telegram, nome do projeto, canal etc ... ).

## **Service:**
---
  Os **Serviços:**  sao os controllers do framework, sao responsaveis por contrar os fluxos da plataforma
  
  No exemplo abaixo, iremos construir um service:
 ```php
<?php

use Source\Http\Response\API;
use Source\Router\Router;
use Source\Attributes\Permission;

public function  __construct( private Router $router ) {}

#[Response(API::class)]
class ClientService{

    #[Permission(
      service: Permission::CLIENT,
      level: Permission::READ
      )]
    public function getClient(Request $request ) : array
    {
      $inputs =  $request->inputs();
       if($inputs["idCliente"] == 1){
            return [
                "message" => "cliente encontrado com sucesso"
            ];
       }

       throw new \Exception("Cliente nao encontrado!");

    }
 ```
 Acima vemos dois tipos te atributos 'Response' e 'Permission'
 - **Response:** é responsavel por controlar o fluxo de saida da plataforma, é passado uma classe como parametro, esta classe possui uma funcao nomeada de handler, que carrega a execucao do controller.
- **Permission:** Passa para o controller os niveis de acesso que o usuario precisa ter para acessar a rota, as constantes podem ser atualizadas ou incrementadas em
  -  level: ````Source\Permission\PermissionLevelInterface````
  -  Service: ````Source\Permission\PermissionServiceInterface````
  -  type: ````Source\Permission\PermissionTypeInterface````
  
Nas Permissoes é possivel definir o nivel de permissao, e o serviço. O nivel por defaut é de 1 a 4 (respectivamente representados por, READ, CREATE, UPDATE, DELETE).O service, represental qual o serviço necessario para o  usuario acessar a rota.

Na chamada da funçao pela rota é passado um objeto do tipo Request, onde é possivel acessar os inputs (dados enviados pela requisiçao), e acessar dados retornados pelo middlewares.

```php
    $request->inputs();
    $request->middleWare("Permission") /** @return string[] */
```
## **Retorno Formatado:**
---
Ao final da execuçao do serviço é possivel retornar o que será tratado pela classe que foi atribuida em ```Response``` . Por Default (utilizando a classe API, para controlar o fluxo de saida das informaçoes) esse array será formatado em json e retornará para o cliente, com codigo HTTP 200.

**Ex:** 
```php
return [
  "message" => "lista gerada com sucesso!",
  "data" => [
    "lista1" => 1,
    "lista2" => 2
  ]
];
 ```
 **Resultará em:**


 ```json
 {
  "status": true,
  "message": "lista gerada com sucesso!",
  "data": {
    "lista1": 1,
    "lista2": 2
  }
}

- HTTP code: 200
 ```

## **Exceptions:**
---

Quando for lançado uma exception o sistema trata como retorno falso. A execuçao do codigo é parada e retornado a mensagem para o cliente com o codigo HTTP informado. 

**Ex:**
```php
  throw new Exception("Cliente nao encontrado", 400);
 ```

 **Resultara em:**

 
 ```json
 {
  "status": false,
  "message": "Cliente nao encontrado!"
}

- HTTP code: 400

```

## **Integraçãos com Telegram:**
---

A estrutura do sistema é capaz de capturar erros internos (erros de tipagem, classes nao encontradas, sintaxes incorretas), erros de SQL, e Erros definidos pelo proprio usuario do framework e envia-los via telegram para um grupo definido no arquivo .env

## ***Testes:***
---
Sempre que um objeto for criado dentro do sistema, ou alterado é necessario validar seus metodos utilizando PHPUnit para evitar a quebra de suas funcionalidades.
- Os testes efetuados ficam localizados na pasta ```tests``` na raiz do projeto, e suas classes carregam o sulfixo test em sua nomenclatura.

### **Basico na linha de comando**
O comando abaixo é responsavel por executar todos os testes  

```console
  meu@pa:~s ./vendor/bin/phpunit --color tests
```
O retorno esperado é algo parecido com isso:

```console
 OK (112 tests, 121 assertions)
```
Caso venha algo como mostrado abaixao, sera necessario efetuar ajustes.

```console
Tests: 112, Assertions: 121, Failures: 1.
```
 



