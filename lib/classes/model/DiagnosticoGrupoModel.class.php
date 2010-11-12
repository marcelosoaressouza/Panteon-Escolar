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

class DiagnosticoGrupoModel extends PanteonEscolarBaseModel
{
  private $_texto_diagnostico_grupo;
  private $_data_hora_cadastro_diagnostico_grupo;
  private $_data_hora_ultima_atualizacao_diagnostico_grupo;

  private $_id_tema_panteon;
  private $_id_grupo;

  public function getDataHoraUltimaAtualizacaoDiagnosticoGrupo() {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraUltimaAtualizacaoDiagnosticoGrupo($data_hora_ultima_atualizacao_diagnostico_grupo) {
    $this->_data_hora_ultima_atualizacao_diagnostico_grupo = date("Y-m-d H:i:s");
  }

  public function getTextoDiagnosticoGrupo() {
    return $this->_texto_diagnostico_grupo;
  }

  public function setTextoDiagnosticoGrupo($texto_diagnostico_grupo) {
    $this->_texto_diagnostico_grupo = $texto_diagnostico_grupo;
  }

  public function getDataHoraCadastroDiagnosticoGrupo() {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroDiagnosticoGrupo($data_hora_cadastro_diagnostico_grupo) {
    $this->_data_hora_cadastro_diagnostico_grupo = date("Y-m-d H:i:s");
  }

  public function getIDGrupo() {
    return $this->_id_grupo;
  }

  public function setIDGrupo($id_grupo) {
    $this->_id_grupo = $id_grupo;
  }

  public function getIDTemaPanteon() {
    return $this->_id_tema_panteon;
  }

  public function setIDTemaPanteon($id_tema_panteon) {
    $this->_id_tema_panteon = $id_tema_panteon;
  }

}

?>
