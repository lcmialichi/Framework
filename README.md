# Atualização ATLAS 17-11

- Conteinerização de dependencias
- Helpers (novas funcoes, que podem ser chamadas em qualquer lugar da aplicação)
- Providers (Irao dizer como uma dependencia especifica sera resolvida na execuçao da aplicaçao).
- Injeção de dependencias automaticas em controllers
- Criado nova classe para lidar com request (Responsabilidade nao pertence mais a rota)
- Obrigatorio enviar Content-Type

### Simulacao ATLAS
#### Requisicao:
    Nao houveram mudanças na requisição.
#### Resposta:
  ```json
    {
    "status": true,
    "message": "Simulacao efetuada com sucesso",
    "data": {
        "data": "2022-11-09",
        "valor": 6818.54,
        "qntParcelas": 10,
        "valorBruto": 10836,
        "iof": null,
        "parcelas": [
            {
                "numero": 1,
                "valor": 2786.74,
                "data": "2023-01-01"
            },
            {
                "numero": 2,
                "valor": 2274.98,
                "data": "2024-01-01"
            },
            {
                "numero": 3,
                "valor": 1819.98,
                "data": "2025-01-01"
            },
            {
                "numero": 4,
                "valor": 1358.98,
                "data": "2026-01-01"
            },
            {
                "numero": 5,
                "valor": 951.29,
                "data": "2027-01-01"
            },
            {
                "numero": 6,
                "valor": 665.9,
                "data": "2028-01-01"
            },
            {
                "numero": 7,
                "valor": 466.13,
                "data": "2029-01-01"
            },
            {
                "numero": 8,
                "valor": 285.06,
                "data": "2030-01-01"
            },
            {
                "numero": 9,
                "valor": 151.29,
                "data": "2031-01-01"
            },
            {
                "numero": 10,
                "valor": 75.65,
                "data": "2032-01-01"
            }
        ]
    }
```

### Digitaçao ATLAS
---

#### Requisicao:
    Nao houveram mudanças na requisição.
#### Resposta:
    Nao houveram mudanças na resposta.

### Conteinerização de dependencias
---
Agora é necessario informar na tipagem do controller qual classe será instanciada,para que o container possa resolver as dependencias.
vantagens: multiplas classes podem ser resolvidas em cascatas , e a maneira em que serao resolvidas podem ser definidas pelos providers 

#### exemplo:
Criaçao de um controller:

```php 

class UserController{

    public function __construct(Router $router){
        // Poderia ser router, ou request, ou user,
        // ou qualuqer outra classe que o container possa resolver

        //Alem do mais nao precisa ser uma dependencia apenas.
    }

    public function consult(
        Request $request,
        Router $router, 
        Client $cliente, 
        User $user){
            // todas essas dependencias poderiam ser resolvidas
    }
}

        
```



   - tambem é possivel resolver uma dependencia utilizando o metodo 'resolve' ( em qualquer lugar da execuçao da aplicaçao, nao so apenas dentro de parametros)
  
  ```php
  $request = resolve(Request::class);
  $request->inputs();

  ```
  - é possivel resolver uma classe e um metodo da seguinte forma
  
  ```php
    $functionSolved = container()->call(ClientService::class . "@consultFullData")
````
 ### Providers

 - é possivel definir como uma dependencia deve ser resolvida apenas criando uma classe em "Resources\Providers" o metodo de execução deve se chamar 'register' e deve herdar 'Container'

```php
class SimulationProvider extends Provider {
    
        public function register(Request $request)
        {
            
            if(empty($dadosSimulacao = $request->dadosSimulacao)){
                return;
            }

            $this->app->bind(SimulationObject::class, function() use ($dadosSimulacao){
                return new SimulationObject(
                    idTipo: $dadosSimulacao["idTipo"],
                    valor: $dadosSimulacao["valor"],
                    prazo: $dadosSimulacao["prazo"],
                    margem: $dadosSimulacao["margem"],
                    renda: $dadosSimulacao["renda"],
                    seguro: (int)$dadosSimulacao["seguro"],
                    idTabelaBanco: $dadosSimulacao["idTabelaBanco"]
                );
            });
        }
}

````

### Helpers

Script que contem diversos metodos que podem ser chamados em qualquer lugar da aplicação.
Localizado em "Resources".

### Novo Request
---

A nova classe de request, possui metodos novos, alterando a finalidade de alguns, e novos recursos
(O request antigo, do robson leite permanece, mas a ideia é ir removendo aos poucos, poderia ser feito em pouco tempo,  mas preguiça??? ).

#### Exemplos:

```php
    public function consult(Request $request){
        
        #todos os dados (input, queryParams, rota)
        $request->all();

        #todos os dados que vieram do CORPO
        $request->inputs();

        #retorna o dado utilizando dot notation
        $request->inputs("dadosCliente.endereco.rua");

        #todos os parametros de url
        $request->query();

        #uma variavel de url
        $request->query("cpf");

        #retorna a rota resolvidas
        $request->route();

        #retorna uma variavel da rota
        $request->route("hashCliente")

        /**
         * É possivel acessar todos os dados do request 
         * (rota, input, e query) como propriedades do objeto
         * exemplo abaixo:
         **/

        #retorna direto do input
        $request->dadosCliente;
        #retorna direto da rota
        $request->hashCliente
         #retorna direto da url
        $request->cpf

        /**
         * Tambem é possivel acessar objeto do
         * usuario que esta efetuando a requisicao.
         * 
         */
        $user = $request->user();
        $user->id();
        $user->type();
        $user->level();

        //Acima sao funcionalidades que coloquei para substitui 
        //o que antes era: '$request->middleware(Permission)'["type"]

    }

```

### Adicionais
---
Criei alguns providers que ja podem ser usados, para facilitar varias coisinhas

#### Source\DTO\Client
```php
    public function consult(Client $client){
        
        // Essa dependencia retorna TODOS os dados do cliente
        // em formato de transfer object caso, na rota, possua 
        // uma variavel {hashCliente}. 

        // caso o cliente nao possua todos os daodos 
        // exemplo:dados bancarios, a propriedade do objeto
        // possuira valor FALSE

    }
```
#### Source\DTO\CreditConditions
```php
    public function consult(CreditConditions $creditConditions){
        
        // Essa dependencia retorna as condiçoes de credito
        // em formato de transfer object, para a tabela identificada.
        // caso no request tenha sido envidado:
        // - dadosProposta.idSimulacao (input)
        // - dadosSimulacao.idTabelaBanco (input)

    }
```

#### Source\Enum\Banks | Source\Enum\Operation | Source\Enum\Product
```php
    public function consult(Banks $bank, Operation $operation, Product $product){
        
        // Para essas 3 dependencias, caso seja identificado 
        // as mesmas condiçoes de 'Source\DTO\CreditConditions', retornara:
        // $bank instanciado com o enum do banco em andamento
        // $operation instanciado com o enum da operacao em andamento
        // $product instanciado com o enum do produto em andamento

    }
```
Lembrando que os providers podem ser criados da maneira que voce quiser, contando que retorne a instancia do objeto da clase definida (caso contrario é jogado um erro).

Lembrando tambem que para estes providers acima, se nao existir os dados que eu peço como: idSimulacao ou idTabelaBanco, simplesmente é retornado,e a dependencia sera resolvida automaticamente pelo container.
