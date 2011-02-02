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

class UsuarioModel extends PanteonEscolarBaseModel
{

  private $_nome_completo_usuario;
  private $_senha_usuario;
  private $_email_usuario;
  private $_data_cadastro_usuario;
  private $_login_usuario;
  private $_admin_usuario;

  private $_id_instituicao;

  public function setNomeCompletoUsuario($nome_completo_usuario)
  {
    $this->_nome_completo_usuario = $nome_completo_usuario;
  }

  public function getNomeCompletoUsuario()
  {
    return $this->_nome_completo_usuario;
  }

  public function setSenhaUsuario($senha_usuario)
  {
    $this->_senha_usuario = $senha_usuario;
  }

  public function getSenhaUsuario()
  {
    return $this->_senha_usuario;
  }

  public function setEmailUsuario($email_usuario)
  {
    $this->_email_usuario = $email_usuario;
  }

  public function getEmailUsuario()
  {
    return $this->_email_usuario;
  }

  public function setDataCadastroUsuario($data_cadastro_usuario)
  {
    $this->_data_cadastro_usuario = date("Y-m-d");
  }

  public function getDataCadastroUsuario()
  {
    return date("Y-m-d");
  }

  public function setIDInstituicao($id_instituicao)
  {
    $this->_id_instituicao = $id_instituicao;
  }

  public function getIDInstituicao()
  {
    return $this->_id_instituicao;
  }

  public function setLoginUsuario($login_usuario)
  {
    $this->_login_usuario = $login_usuario;
  }

  public function getLoginUsuario()
  {
    return $this->_login_usuario;
  }

  public function setAdminUsuario($admin_usuario)
  {
    $this->_admin_usuario = $admin_usuario;
  }

  public function getAdminUsuario()
  {
    return $this->_admin_usuario;
  }

}

?>