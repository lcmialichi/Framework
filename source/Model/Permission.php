<?php

namespace Source\Model;

use \Source\Model\ORM\Model as Schema;
use Source\Model\ValueObject\SimplifyedPermissionObject;
/**
 * Permissoes do usuario
 */
#[ORM\Entity("permissao")]
class Permission extends Schema {

  #[ORM\Column(key: ORM\Column::PK)]
  private $permissao_id;
  #[ORM\Column]
  private $permissao_descricao;

   /**
    * Retorna as permissoes de usuario de forma simplificada [ VIEW: permissao perfil e usuario ]
    *@return SimplifyedPermission
    */
   public static function getSimplifyed(int $userId) : SimplifyedPermissionObject
   {    
       return new SimplifyedPermissionObject( Schema::table("permissoes_simplificada")->select()
                                       ->where("simplificada_id_usuario",  $userId )
                                       ->execute() );

   }

}