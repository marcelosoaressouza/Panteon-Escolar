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

class MensagemForumModel extends PanteonEscolarBaseModel
{
  private $_nome_mensagem_forum;
  private $_texto_mensagem_forum;
  private $_data_hora_cadastro_mensagem_forum;

  private $_id_usuario;
  private $_id_forum;
  private $_id_mensagem_resposta;

  public function getNomeMensagemForum()
  {
    return $this->_nome_mensagem_forum;
  }

  public function setNomeMensagemForum($nome_mensagem_forum)
  {
    $this->_nome_mensagem_forum = $nome_mensagem_forum;
  }

  public function getTextoMensagemForum()
  {
    return $this->_texto_mensagem_forum;
  }

  public function setTextoMensagemForum($texto_mensagem_forum)
  {
    $this->_texto_mensagem_forum = $texto_mensagem_forum;
  }

  public function getDataHoraCadastroMensagemForum()
  {
    return $this->_data_hora_cadastro_mensagem_forum;
  }

  public function setDataHoraCadastroMensagemForum($data_hora_cadastro_mensagem_forum)
  {
    $this->_data_hora_cadastro_mensagem_forum = $data_hora_cadastro_mensagem_forum;
  }

  public function getIDUsuario()
  {
    return $this->_id_usuario;
  }

  public function setIDUsuario($id_usuario)
  {
    $this->_id_usuario = $id_usuario;
  }

  public function getIDForum()
  {
    return $this->_id_id_forum;
  }

  public function setIDForum($id_forum)
  {
    $this->_id_forum = $id_forum;
  }

  public function getIDMensagemResposta()
  {
    return $this->_id_mensagem_resposta;
  }

  public function setIDMensagemResposta($id_mensagem_resposta)
  {
    $this->_id_mensagem_resposta = $id_mensagem_resposta;
  }

}

?>
