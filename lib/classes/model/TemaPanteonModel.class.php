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

class TemaPanteonModel extends PanteonEscolarBaseModel
{
  private $_nome_tema_panteon;
  private $_data_hora_atualizacao_tema_panteon;
  private $_data_hora_cadastro_tema_panteon;
  private $_descricao_tema_panteon;
  private $_privado_tema_panteon;
  private $_publicado_tema_panteon;

  private $_id_metodo_analise;
  private $_id_estrutura_social;
  private $_id_usuario;

  public function getNomeTemaPanteon() {
    return $this->_nome_tema_panteon;
  }

  public function setNomeTemaPanteon($nome_tema_panteon) {
    $this->_nome_tema_panteon = $nome_tema_panteon;
  }

  public function getDataHoraAtualizacaoTemaPanteon() {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraAtualizacaoTemaPanteon($data_hora_atualizacao_tema_panteon) {
    $this->_data_hora_atualizacao_tema_panteon = date("Y-m-d H:i:s");
  }

  public function getDataHoraCadastroTemaPanteon() {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroTemaPanteon($data_hora_cadastro_tema_panteon) {
    $this->_data_hora_cadastro_tema_panteon = date("Y-m-d H:i:s");
  }

  public function getDescricaoTemaPanteon() {
    return $this->_descricao_tema_panteon;
  }

  public function setDescricaoTemaPanteon($descricao_tema_panteon) {
    $this->_descricao_tema_panteon = $descricao_tema_panteon;
  }

  public function getIDMetodoAnalise() {
    return $this->_id_metodo_analise;
  }

  public function setIDMetodoAnalise($id_metodo_analise) {
    $this->_id_metodo_analise = $id_metodo_analise;
  }

  public function getIDEstruturaSocial() {
    return $this->_id_estrutura_social;
  }

  public function setIDEstruturaSocial($id_estrutura_social) {
    $this->_id_estrutura_social = $id_estrutura_social;
  }

  public function getIDUsuario() {
    return $this->_id_usuario;
  }

  public function setIDUsuario($id_usuario) {
    $this->_id_usuario = $id_usuario;
  }

  public function getPublicadoTemaPanteon() {
    return $this->_publicado_tema_panteon;
  }

  public function setPublicadoTemaPanteon($publicado_tema_panteon) {
    $this->_publicado_tema_panteon = $publicado_tema_panteon;
  }

  public function getPrivadoTemaPanteon() {
    return $this->_privado_tema_panteon;
  }

  public function setPrivadoTemaPanteon($privado_tema_panteon) {
    $this->_privado_tema_panteon = $privado_tema_panteon;
  }
}

?>
