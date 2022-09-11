## **Pasta ValueObject**

  ### **finalidade:** Armazenar as classes de encapsulamento de dados referentes ao Model 
  - ---
  ### Ex: O exemplo abaixo demostra como uma funcao do Model envia os dados para o encapsulamento:
   ````php
   public function getSimplifyed($userId)
   {
    return new SimplifyedPermission(
            Schema::table("permissoes_simplificada")
                    ->select()
                    ->where("simplificada_id_usuario",  $userId )
                    ->execute()
            );
   }
   ````
 - "SimplifyedPermission" é a classe onde os dados estao sendo encpasulado, ela é o objeto retornado para o caller da funcao acima

    ### No caso acima a funcao "getSimplifyed()" esta sendo chamada pelo controller, segue a sintaze de chamada: 

    ````php    
    $userPermissions = Permission::getSimplifyed($userId)->getPermissions();  