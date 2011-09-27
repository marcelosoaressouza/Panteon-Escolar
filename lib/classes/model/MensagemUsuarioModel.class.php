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

class MensagemUsuarioModel extends PanteonEscolarBaseModel
{
  private $_nome_mensagem_usuario;
  private $_texto_mensagem_usuario;
  private $_data_hora_cadastro_mensagem_usuario;

  private $_id_usuario_orig;
  private $_id_usuario_dest;
  private $_id_mensagem_usuario_resposta;
  private $_id_tema_panteon;

  public function getNomeMensagemUsuario()
  {
    return $this->_nome_mensagem_usuario;
  }

  public function setNomeMensagemUsuario($nome_mensagem_usuario)
  {
    $this->_nome_mensagem_usuario = $nome_mensagem_usuario;
  }

  public function getTextoMensagemUsuario()
  {
    return $this->_texto_mensagem_usuario;
  }

  public function setTextoMensagemUsuario($texto_mensagem_usuario)
  {
    $this->_texto_mensagem_usuario = $texto_mensagem_usuario;
  }

  public function getDataHoraCadastroMensagemUsuario()
  {
    return $this->_data_hora_cadastro_mensagem_usuario;
  }

  public function setDataHoraCadastroMensagemUsuario($data_hora_cadastro_mensagem_usuario)
  {
    $this->_data_hora_cadastro_mensagem_usuario = $data_hora_cadastro_mensagem_usuario;
  }

  public function getIDUsuarioOrig()
  {
    return $this->_id_usuario_orig;
  }

  public function setIDUsuarioOrig($id_usuario_orig)
  {
    $this->_id_usuario_orig = $id_usuario_orig;
  }

  public function getIDUsuarioDest()
  {
    return $this->_id_usuario_dest;
  }

  public function setIDUsuarioDest($id_usuario_dest)
  {
    $this->_id_usuario_dest = $id_usuario_dest;
  }

  public function getIDMensagemUsuarioResposta()
  {
    return $this->_id_mensagem_usuario_resposta;
  }

  public function setIDMensagemUsuarioResposta($id_mensagem_usuario_resposta)
  {
    $this->_id_mensagem_usuario_resposta = $id_mensagem_usuario_resposta;
  }

  public function getIDTemaPanteon()
  {
    return $this->_id_tema_panteon;
  }

  public function setIDTemaPanteon($id_tema_panteon)
  {
    $this->_id_tema_panteon = $id_tema_panteon;
  }

}

?>
