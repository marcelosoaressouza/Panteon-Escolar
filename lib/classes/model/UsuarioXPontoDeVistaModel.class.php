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

class UsuarioXPontoDeVistaModel extends PanteonEscolarBaseModel
{
  private $_data_hora_usuario_x_ponto_de_vista;
  private $_id_usuario;
  private $_id_ponto_de_vista;
  private $_id_tema_panteon;
  private $_coletado_usuario_x_ponto_de_vista;
  private $_texto_usuario_x_ponto_de_vista;

  public function setIDUsuario($id_usuario) {
    $this->_id_usuario = $id_usuario;
  }

  public function getIDUsuario() {
    return $this->_id_usuario;
  }

  public function setIDPontodeVista($id_ponto_de_vista) {
    $this->_id_ponto_de_vista = $id_ponto_de_vista;
  }

  public function getIDPontodeVista() {
    return $this->_id_ponto_de_vista;
  }

  public function setIDTemaPanteon($id_tema_panteon) {
    $this->_id_tema_panteon = $id_tema_panteon;
  }

  public function getIDTemaPanteon() {
    return $this->_id_tema_panteon;
  }

  public function setDataHoraUsuarioXPontoDeVista($data_hora_usuario_x_ponto_de_vista) {
    $this->_data_hora_usuario_x_ponto_de_vista = date("Y-m-d H:i:s");
  }

  public function getDataHoraUsuarioXPontoDeVista() {
    return date("Y-m-d H:i:s");
  }

  public function setColetadoUsuarioXPontoDeVista($coletado_usuario_x_ponto_de_vista) {
    $this->_coletado_usuario_x_ponto_de_vista = $coletado_usuario_x_ponto_de_vista;

  }

  public function getColetadoUsuarioXPontoDeVista() {
    return $this->_coletado_usuario_x_ponto_de_vista;

  }

  public function setTextoUsuarioXPontoDeVista($texto_usuario_x_ponto_de_vista) {
    $this->_texto_usuario_x_ponto_de_vista = $texto_usuario_x_ponto_de_vista;

  }

  public function getTextoUsuarioXPontoDeVista() {
    return $this->_texto_usuario_x_ponto_de_vista;

  }

}

?>