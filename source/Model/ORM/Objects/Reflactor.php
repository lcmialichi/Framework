<?php

namespace Source\Model\ORM\Objects;

use Source\Model\ORM\ORMInterface;

class Reflactor
{
    private \ReflectionClass $entityReflected;

    public function __construct( 
        private ORMInterface $entity 
    )
    {
        $this->entityReflected = new \ReflectionClass($this->entity);
    }

    /**
     * @param string $attributeName - Nome do atributo da classe
     * @return string|null
     */
    public function getClassAttribute( string $attributeName ) : string|null
    {
        $attribute = $this->entityReflected->getAttributes($attributeName);
        if(!$attribute){
            return false;
        }
        
        return $attribute[0]->getArguments()[0];

    }

    /**
     * @param string $attributeName - Nome do atributo que sera procurado nas propriedades
     * @return array|null
     */
    public function getPropertiesAttributes(string $attributeName) : array|null
    {
        $properties = $this->entityReflected->getProperties();

         foreach($properties as $propertie){
             $columnAttribute = $propertie->getAttributes($attributeName);

             if($columnAttribute){
                 $propertyName = $propertie->getName();
                 $args = $columnAttribute[0]->getArguments();
 
                $columns[$propertyName] = [];
                 if(!empty($args)){
                    foreach($args as $arg => $value){
                        $columns[$propertyName][$arg] =  $value;
                    }                   
                 }
             }
         }

         return $columns;
    }

    /**
     * Coleta a o valor da propriedade da classe refletida
     * @return string|false
     */
    public function getProperty( string $key )
    {
        $property = $this->entityReflected->getProperty($key); 
        if($property){
            $property->setAccessible(true);
           return $property->getValue($this->entity);

        }

        return false;
    
    }

    /**
     * Atribui valores aos atributos da classe refletida
     */
    public function setProperty( string $key, mixed $value) : bool
    { 
        $property = $this->entityReflected->getProperty($key); 
        if($property){
            $property->setAccessible(true);
            $property->setValue( $this->entity , $value ); 
            return true;
        }
       
        return false;
    }
}