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

class MidiatecaXPontoDeVistaModel extends PanteonEscolarBaseModel
{
  private $_id_midiateca;
  private $_id_ponto_de_vista;

  public function setIDMidiateca($id_midiateca)
  {
    $this->_id_midiateca = $id_midiateca;
  }

  public function getIDMidiateca()
  {
    return $this->_id_midiateca;
  }

  public function setIDPontoDeVista($id_ponto_de_vista)
  {
    $this->_id_ponto_de_vista = $id_ponto_de_vista;
  }

  public function getIDPontoDeVista()
  {
    return $this->_id_ponto_de_vista;
  }

}

?>
