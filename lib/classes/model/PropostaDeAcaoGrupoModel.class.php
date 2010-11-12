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

class PropostaDeAcaoGrupoModel extends PanteonEscolarBaseModel
{
  private $_texto_proposta_de_acao_grupo;
  private $_data_hora_cadastro_proposta_de_acao_grupo;
  private $_id_grupo;


  public function getTextoPropostaDeAcaoGrupo() {
    return $this->_texto_proposta_de_acao_grupo;
  }

  public function setTextoPropostaDeAcaoGrupo($texto_proposta_de_acao_grupo) {
    $this->_texto_proposta_de_acao_grupo = $texto_proposta_de_acao_grupo;
  }

  public function getDataHoraCadastroPropostaDeAcaoGrupo() {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroPropostaDeAcaoGrupo($data_hora_cadastro_proposta_de_acao_grupo) {
    $this->_data_hora_cadastro_proposta_de_acao_grupo = date("Y-m-d H:i:s");
  }

  public function getIDGrupo() {
    return $this->_id_grupo;
  }

  public function setIDGrupo($id_grupo) {
    $this->_id_grupo = $id_grupo;
  }

}

?>
