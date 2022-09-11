<?php

use Tests\ValueObjectsExecuter;
use PHPUnit\Framework\TestCase;
use Source\Service\ValueObject;
use Source\Exception;

class ValueObjectsExceptionTest extends TestCase{

    private $clientObject;
    private $clientUserObject;
    private $clientMailObject;
    private $clientContactObject;
    private $clientBankObject;
    private $clientEmployerObject;
    private $clientAddressObject;
    private $clientDocumentObject;
    private $acceptObject;
    private $attendanceLogObject;
    private $attendanceObject;
    private $convenantObject;
    private $credentialObject;
    private $departmentObject;
    private $proposalObject;
    private $simulationObject;
    private $tabLogObject;

    public function objectSetUp() : void
    {
        $this->clientObject = new ValueObject\ClientObject(
            nome: 123,
            cpf: "string",
            sexo: "errado",
            estadoCivil: "errado",
            nacionalidade: "errado",
            nomeMae: 123,
            hash: null,
            dataNascimento: "isso aqui nao é data"
        );
        $this->clientUserObject = new ValueObject\ClientUserObject(
            idCliente: null,
            usuario: null,
            senha: null,
        );
        $this->clientMailObject = new ValueObject\ClientMailObject(
            idCliente: null,
            email: null
        );
        $this->clientContactObject = new ValueObject\ClientContactObject(
            idCliente: null,
            telefone: null,
            ddd: null,
        
        );
        $this->clientBankObject = new ValueObject\ClientBankObject();
        $this->clientEmployerObject = new ValueObject\ClientEmployerObject();
        $this->clientAddressObject = new ValueObject\ClientAddressObject(
            cidade: "",
            bairro: 1,
            rua: "",
            complemento: null,
            uf: "",
            numero: "aaaaaaaaaaaaaaaaaaaaa",
            cep: 1111111111111111111111111111,
        );
        $this->clientDocumentObject = new ValueObject\ClientDocumentObject();
        $this->acceptObject = new ValueObject\AcceptObject();
        $this->attendanceLogObject = new ValueObject\AttendanceLogObject();
        $this->attendanceObject = new ValueObject\AttendanceObject();
        $this->convenantObject = new ValueObject\ConvenantObject();
        $this->credentialObject = new ValueObject\CredentialObject(); /** @todo Nao possui validaçoes ainda */
        $this->departmentObject = new ValueObject\DepartmentObject();
        $this->proposalObject = new ValueObject\ProposalObject();
        $this->simulationObject = new ValueObject\SimulationObject();
        $this->tabLogObject = new ValueObject\TabLogObject();
    }

    /**
     * @return array
     */
    public function provider() : array
    {   
        $this->objectSetUp();
        return [
            ...$this->createProvider($this->clientObject, Source\Exception\ClientException::class),
            ...$this->createProvider($this->clientUserObject, Source\Exception\ClientException::class),
            ...$this->createProvider($this->clientMailObject, Source\Exception\ClientException::class),
            ...$this->createProvider($this->clientContactObject, Source\Exception\ClientException::class),
            ...$this->createProvider($this->clientBankObject, Source\Exception\ClientException::class),
            ...$this->createProvider($this->clientEmployerObject, Source\Exception\ClientException::class),
            ...$this->createProvider($this->clientAddressObject, Source\Exception\ClientException::class),
            ...$this->createProvider($this->clientDocumentObject, Source\Exception\ClientException::class),
            ...$this->createProvider($this->attendanceLogObject, Source\Exception\LogError::class),
            ...$this->createProvider($this->attendanceObject, Source\Exception\AttendanceException::class, unsets: ["getId", "getStatus"]),
            ...$this->createProvider($this->acceptObject, Source\Exception\AcceptError::class, unsets: ["getId", "getIdAcao"]),
            ...$this->createProvider($this->departmentObject, Source\Exception\EmployerException::class),
            ...$this->createProvider($this->proposalObject, Source\Exception\ProposalException::class),
            ...$this->createProvider($this->simulationObject, Source\Exception\SimulationException::class),
            ...$this->createProvider($this->tabLogObject, Source\Exception\LogException::class),
            ...$this->createProvider($this->convenantObject, Source\Exception\EmployerException::class)            
        ];  
              
    }

     /**
     * @test
     * @dataProvider provider
     * @todo especificar instancia de erro
     * @return void
     */
    public function value_object_teste_exception($functions, $exception) 
    {
        $this->expectException($exception);
        $functions();
        
    }

     /**
     * cria os parametros que serao enviados para teste
     *
     * @param object $object
     * @param string $exception
     * @return array
     */
    public function createProvider($object, $exception, $unsets = ["getId"]){

        $executer = (new ValueObjectsExecuter($object));
        $data = $executer->getGetter();

        foreach($unsets as $unset){
            if(in_array($unset, $data)){
                $executer->unsetGetter($unset);
            }
        }
        
        $data = $executer->getGetter();

        $classProvider = [];
        foreach($data as $function){
            $classProvider[] = [
                $object->{$function}(...),
                $exception
            ];

        }

        return $classProvider;
    }


}