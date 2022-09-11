<?php

namespace Source\Model\ORM;

use Source\Model\ORM\Objects\Reflactor;
use Source\Connection\DBConnect as DB;

/**
 * Estabelece conexao unica com o banco para toda a execucao do codigo
 * 
 * 
 * 
 * ![](https://upload.wikimedia.org/wikipedia/commons/6/63/Icon_Bird_512x512.png)
*/
abstract class Model implements ORMInterface {

    /**
     * Variavel contendo o nome da tabela
     * @var string
     */
    private string $tableName;
    /**
     * Array contendo o nome dos campos das tabelas
     * @var array
     */
    private array $columns;
    /**
     * Nome da Primary key da tabela
     * @var string
     */
    private string $primaryKey; 
    /**
     * EM TESTE 
     */
    private static $connection = false;

    public function __construct() {

        if(!Model::$connection){ # uma conexao por execucao de codigo
            Model::$connection = DB::getConnection();
        }

        $this->reflactor = new Reflactor($this);
        $this->tableName =  $this->reflactor->getClassAttribute(Entity::class);
        $columns =  $this->reflactor->getPropertiesAttributes(Column::class);

        $keys = array_filter($columns, function ($column) {
            return isset($column["key"]) ?  true : false;

        });
        
        foreach($keys as $key => $value){
            if($value['key'] == Column::PK){
                $this->primaryKey = $key;
            }
        }

        $this->columns = $columns;

    }
    

    /**
     * Atribui valores para as propriedades
     * passando valores $key (nome da propriedade)
     * @return void
     */
    private function setProperty( string $key, mixed $value ) : void
    {
       if(in_array($key, array_keys($this->columns)) || $key == $this->primaryKey){
            $this->reflactor->setProperty($key, $value);
   
       }
    }   

    /**
     * Coleta valores da propriedade
     * @return mixed
     */
    private function getProperty( string $key ) : mixed
    {
        if(in_array($key, array_keys($this->columns)) || $key == $this->primaryKey){
            $property = $this->reflactor->getProperty($key);
            if($property){
                return $property;
            }
       }

       return false;
    }

    /**
     * Faz insert dos dados da classe e 
     * atribui os valores de volta as propriedades
     * Nao faz insert das colunas com generatedValue marcados
     * @return int
     */
    public function save() : int
    {           
        foreach(array_keys($this->columns) as $column){
            if($this->primaryKey != $column && $this->columns[$column]["generatedValue"] != true){
                $insertDataColumns[$column] =  $this->reflactor->getProperty($column);
            }
            
        }
        
        $id =  Model::$connection->table($this->tableName)->insert($insertDataColumns)->execute();
        if($id){
            $this->setProperty($this->primaryKey, $id);
            return $id;
        }

        return false;
                   
    }

    /**
     * Procura pela primaryKey e retorna os valores de volta as propriedades
     * @param bool $useAlias usa alias habilitado nos atributos da classe
     * @return object|bool
     */
    public function find( int $id, bool $useAlias = false)
    {
        $get = null;
        if($useAlias){
            foreach($this->columns as $column => $key){
                $get[$column] = $key['alias'] ?? $column;
            }
        }

        $DBResponse =  Model::$connection->table($this->tableName)->select($get)->where($this->primaryKey, $id)->one();
        if(!$DBResponse){
           return false;
        }
        $this->import($DBResponse);
        return $DBResponse;
    }

    /**
     * Execucao de query sem restricoes
     * @param string $tableName
     * @return DB
     */
    public static function table( string  $tableName )
    {
        return Model::$connection->table($tableName);

    }

    /**
     * Execucao de select atravez da tabela de entidade
     * @param mixed $fields
     * @return DB
     */
    public function select( mixed $get = null, bool $useAlias = false )
    {
        if($useAlias){
            foreach($this->columns as $column => $key){
                $get[$column] = $key['alias'] ?? $column;
            }
        }

        return Model::$connection->table($this->tableName)->select($get);

    }

    /**
     * Updade da tabela de entidade instanciada
     * @param array values
     * @return DB
     */
    public function update( array $values )
    {
        return Model::$connection->table($this->tableName)->update($values);

    }

     /**
     * Updade da tabela de entidade instanciada pela primaryKey
     * @param array data
     * @param int $id
     * @return DB
     */
    public function updateModel( array $data, int $id,)
    {
        return Model::$connection->table($this->tableName)
                                 ->update($data)
                                 ->where($this->primaryKey, $id)
                                 ->execute();
    }


      /**
     * Updade da tabela de entidade instanciada
     * @param array values
     * @return DB
     */
    public function insert( array $values )
    {
        return Model::$connection->table($this->tableName)->insert($values);

    }

    /**
     * Select de toda a tabela da entidade instanciada
     * @return array|bool
     */
    public function all( bool $useAlias = false)
    {

        $get = null;
        if($useAlias){
            foreach($this->columns as $column => $key){
                $get[$column] = $key['alias'] ?? $column;
            }
        }

       return Model::$connection->table($this->tableName)->select($get)->execute();         
  
    }

    /**
     * retorna a primaryKey da entidade 
     * @return int
     */
    public function getId()
    { 
        return $this->getProperty($this->primaryKey);
    }

    /**
     * Importa o retorno do BD para as propriedades da classe
     * @return void
     */
    public function import(object $data) : void
    {
        foreach(get_object_vars($data) as $key => $value){
            $this->setProperty($key, $value);
        }
    }


}