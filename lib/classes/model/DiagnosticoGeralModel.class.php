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

class DiagnosticoGeralModel extends PanteonEscolarBaseModel
{
  private $_texto_diagnostico_geral;
  private $_data_hora_cadastro_diagnostico_geral;
  private $_id_usuario_x_tema_panteon;

  public function getDataHoraUltimaAtualizacaoDiagnosticoGeral() {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraUltimaAtualizacaoDiagnosticoGeral($data_hora_ultima_atualizacao_diagnostico_geral) {
    $this->_data_hora_ultima_atualizacao_diagnostico_geral = date("Y-m-d H:i:s");
  }

  public function getTextoDiagnosticoGeral() {
    return $this->_texto_diagnostico_geral;
  }

  public function setTextoDiagnosticoGeral($texto_diagnostico_geral) {
    $this->_texto_diagnostico_geral = $texto_diagnostico_geral;
  }

  public function getIDUsuarioXTemaPanteon() {
    return $this->_id_usuario_x_tema_panteon;
  }

  public function setIDUsuarioXTemaPanteon($id_usuario_x_tema_panteon) {
    $this->_id_usuario_x_tema_panteon = id_usuario_x_tema_panteon;
  }

}

?>