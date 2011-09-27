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

class EstadoModel extends PanteonEscolarBaseModel
{
  private $_uf_estado;
  private $_nome_estado;

  public function getUfEstado()
  {
    return $this->_uf_estado;
  }

  public function setUfEstado($uf_estado)
  {
    $this->_uf_estado = $uf_estado;
  }

  public function getNomeEstado()
  {
    return $this->_nome_estado;
  }

  public function setNomeEstado($nome_estado)
  {
    $this->_nome_estado = $nome_estado;
  }

}

?>
