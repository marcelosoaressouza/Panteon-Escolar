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

class SujeitoModel extends PanteonEscolarBaseModel
{
  private $_nome_sujeito;
  private $_descricao_sujeito;
  private $_data_hora_cadastro_sujeito;
  private $_caminho_foto_sujeito;

  private $_id_tema_panteon;
  private $_id_grupo_social;

  public function getNomeSujeito() {
    return $this->_nome_sujeito;
  }

  public function setNomeSujeito($nome_sujeito) {
    $this->_nome_sujeito = $nome_sujeito;
  }

  public function getDataHoraCadastroSujeito() {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroSujeito($data_hora_cadastro_sujeito) {
    $this->_data_hora_cadastro_sujeito = date("Y-m-d H:i:s");
  }

  public function getDescricaoSujeito() {
    return $this->_descricao_sujeito;
  }

  public function setDescricaoSujeito($descricao_sujeito) {
    $this->_descricao_sujeito = $descricao_sujeito;
  }

  public function getCaminhoFotoSujeito() {
    return $this->_caminho_foto_sujeito;
  }

  public function setCaminhoFotoSujeito($caminho_foto_sujeito) {
    $this->_caminho_foto_sujeito = $caminho_foto_sujeito;
  }

  public function setIDTemaPanteon($id_tema_panteon) {
    $this->_id_tema_panteon = $id_tema_panteon;
  }

  public function getIDTemaPanteon() {
    return $this->_id_tema_panteon;
  }

  public function setIDGrupoSocial($id_grupo_social) {
    $this->_id_grupo_social = $id_grupo_social;
  }

  public function getIDGrupoSocial() {
    return $this->_id_grupo_social;
  }
}

?>
