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

class SituacaoProblemaModel extends PanteonEscolarBaseModel
{
  private $_nome_situacao_problema;
  private $_descricao_situacao_problema;

  private $_id_tema_panteon;

  public function getNomeSituacaoProblema() {
    return $this->_nome_situacao_problema;
  }

  public function setNomeSituacaoProblema($nome_situacao_problema) {
    $this->_nome_situacao_problema = $nome_situacao_problema;
  }

  public function getDescricaoSituacaoProblema() {
    return $this->_descricao_situacao_problema;
  }

  public function setDescricaoSituacaoProblema($descricao_situacao_problema) {
    $this->_descricao_situacao_problema = $descricao_situacao_problema;
  }

  public function getIDTemaPanteon() {
    return $this->_id_tema_panteon;
  }

  public function setIDTemaPanteon($id_tema_panteon) {
    $this->_id_tema_panteon = $id_tema_panteon;
  }

}

?>
