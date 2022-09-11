<?php

use PHPUnit\Framework\TestCase;
use Source\Model\Product as ProductModel;
use Source\Model\AttendanceLogAction;

final class EnumTest extends TestCase{

    /**
     * @test
     * Testa se todos os produto estao cadastrados no BD
     * se deu ruim aqui é pq foi criado um enum de produto que nao existe no banco
     * @return void
     */
    public function todos_os_produtos_dos_enums_existem_no_bd(){

        $cases = Source\Enum\Product::cases();
        $ids = array_column( $cases, "value");
        $productsInDb =  array_map( function($product){ 
            return $product->id;

        },(new ProductModel)->all(useAlias: true));
    
        $this->assertEquals($ids, $productsInDb);
        
    }

    /**
     * @test
     * @return void
     */
    public function todos_as_acoes_no_log_de_atendimento_dos_enums_existem_no_bd(){

        $cases = Source\Enum\AttendanceLogAction::cases();
        $ids = array_column( $cases, "value");
        $actionInDb =  array_map( function($action){
            return $action->id;

        },(new AttendanceLogAction)->all(useAlias: true));
    
        $this->assertEquals($ids, $actionInDb);

    }

}

