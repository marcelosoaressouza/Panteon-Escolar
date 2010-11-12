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

class NotaModel extends PanteonEscolarBaseModel
{
  private $_nome_nota;
  private $_texto_nota;
  private $_data_hora_cadastro_nota;

  private $_id_usuario;
  private $_id_tema_panteon;

  public function getNomeNota() {
    return $this->_nome_nota;
  }

  public function setNomeNota($nome_nota) {
    $this->_nome_nota = $nome_nota;
  }

  public function getTextoNota() {
    return $this->_texto_nota;
  }

  public function setTextoNota($texto_nota) {
    $this->_texto_nota = $texto_nota;
  }

  public function getDataHoraCadastroNota() {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroNota($data_hora_cadastro_nota) {
    $this->_data_hora_cadastro_nota = date("Y-m-d H:i:s");
  }

  public function setIDUsuario($id_usuario) {
    $this->_id_usuario = $id_usuario;
  }

  public function getIDUsuario() {
    return $this->_id_usuario;
  }

  public function setIDTemaPanteon($id_tema_panteon) {
    $this->_id_tema_panteon = $id_tema_panteon;
  }

  public function getIDTemaPanteon() {
    return $this->_id_tema_panteon;
  }

}

?>
