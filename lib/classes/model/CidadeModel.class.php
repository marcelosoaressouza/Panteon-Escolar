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

class CidadeModel extends PanteonEscolarBaseModel
{
  private $_uf;
  private $_nome_cidade;

  public function getUf()
  {
    return $this->_uf;
  }

  public function setUf($uf)
  {
    $this->_uf = $uf;
  }

  public function getNomeCidade()
  {
    return $this->_nome_cidade;
  }

  public function setNomeCidade($nome_cidade)
  {
    $this->_nome_cidade = $nome_cidade;
  }

}

?>