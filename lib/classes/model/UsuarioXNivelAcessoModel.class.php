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

class UsuarioXNivelAcessoModel extends PanteonEscolarBaseModel
{
  private $_nome_usuario_x_nivel_acesso;
  private $_valor_usuario_x_nivel_acesso;
  private $_id_usuario;

  public function setIDUsuario($id_usuario) {
    $this->_id_usuario = $id_usuario;
  }

  public function getIDUsuario() {
    return $this->_id_usuario;
  }

  public function setNomeUsuarioNivelAcesso($_nome_usuario_x_nivel_acesso) {
    $this->_nome_usuario_x_nivel_acesso = $_nome_usuario_x_nivel_acesso;
  }

  public function getNomeUsuarioNivelAcesso() {
    return $this->_nome_usuario_x_nivel_acesso;
  }

  public function setValorUsuarioNivelAcesso($_valor_usuario_x_nivel_acesso) {
    $this->_valor_usuario_x_nivel_acesso = $_valor_usuario_x_nivel_acesso;
  }

  public function getValorUsuarioNivelAcesso() {
    return $this->_valor_usuario_x_nivel_acesso;
  }

}

?>