<?php

namespace Source\Permission;

/*
|--------------------------------------------------------------------------
|SERVICO PERMISSAO
|--------------------------------------------------------------------------
| aqui pode mexer, adicionar ou remover quais permissoes
| existem na plataforma (referente ao id da permissao no banco de dados)
*/

interface PermissionServiceInterface{
    
const USER = 1;
const CLIENT = 2;
const ATTENDANCE = 3;
const SIMULATION = 4;
const CHANNEL = 5;
const PROPOSAL = 6;
const TAB = 7;
const LGPD_ACCEPT = 8;
const EMPLOYER = 9;

}