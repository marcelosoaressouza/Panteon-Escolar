<?php

/*
*
* Panteon Escolar
*
* Yuri Wanderley (yuri.wanderley at gmail.com)
* Tarcisio Araujo (tatauphp at gmail.com)
* Marcelo Soares Souza (marcelo at juntadados.org)
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* http://www.gnu.org/licenses/gpl-2.0.html
*
*/

class PermissaoModel extends PanteonEscolarBaseModel
{
  private $_nome_permissao;

  public function getNomePermissao() {
    return $this->_nome_permissao;
  }

  public function setNomePermissao($nome_permissao) {
    $this->_nome_permissao = $nome_permissao;
  }

}

?>