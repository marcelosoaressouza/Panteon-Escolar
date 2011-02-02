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

class DiagnosticoIndividualModel extends PanteonEscolarBaseModel
{
  private $_texto_diagnostico_individual;
  private $_data_hora_cadastro_diagnostico_individual;
  private $_data_hora_ultima_atualizacao_diagnostico_individual;

  private $_id_usuario_x_tema_panteon;
  private $_id_item_analise;
  private $_id_situacao_problema;

  public function getDataHoraUltimaAtualizacaoDiagnosticoIndividual()
  {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraUltimaAtualizacaoDiagnosticoIndividual($data_hora_ultima_atualizacao_diagnostico_individual)
  {
    $this->_data_hora_ultima_atualizacao_diagnostico_individual = date("Y-m-d H:i:s");
  }

  public function getTextoDiagnosticoIndividual()
  {
    return $this->_texto_diagnostico_individual;
  }

  public function setTextoDiagnosticoIndividual($texto_diagnostico_individual)
  {
    $this->_texto_diagnostico_individual = $texto_diagnostico_individual;
  }

  public function getDataHoraCadastroDiagnosticoIndividual()
  {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroDiagnosticoIndividual($data_hora_cadastro_diagnostico_individual)
  {
    $this->_data_hora_cadastro_diagnostico_individual = date("Y-m-d H:i:s");
  }

  public function getIDUsuarioXTemaPanteon()
  {
    return $this->_id_usuario_x_tema_panteon;
  }

  public function setIDUsuarioXTemaPanteon($id_usuario_x_tema_panteon)
  {
    $this->_id_usuario_x_tema_panteon = id_usuario_x_tema_panteon;
  }

  public function setIDItemAnalise($id_item_analise)
  {
    $this->_id_item_analise = $id_item_analise;
  }

  public function getIDItemAnalise()
  {
    return $this->_id_item_analise;
  }

  public function setIDSituacaoProblema($id_situacao_problema)
  {
    $this->_id_situacao_problema = $id_situacao_problema;
  }

  public function getIDSituacaoProblema()
  {
    return $this->_id_situacao_problema;
  }

}

?>