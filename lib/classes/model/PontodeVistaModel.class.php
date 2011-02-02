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

class PontodeVistaModel extends PanteonEscolarBaseModel
{
  private $_texto_ponto_de_vista;
  private $_data_hora_cadastro_ponto_de_vista;

  private $_id_sujeito;
  private $_id_item_analise;
  private $_id_situacao_problema;

  public function getTextoPontodeVista()
  {
    return $this->_texto_ponto_de_vista;
  }

  public function setTextoPontodeVista($texto_ponto_de_vista)
  {
    $this->_texto_ponto_de_vista = $texto_ponto_de_vista;
  }

  public function getDataHoraCadastroPontodeVista()
  {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroPontodeVista($data_hora_cadastro_ponto_de_vista)
  {
    $this->_data_hora_cadastro_ponto_de_vista = date("Y-m-d H:i:s");
  }

  public function setIDSujeito($id_sujeito)
  {
    $this->_id_sujeito = $id_sujeito;
  }

  public function getIDSujeito()
  {
    return $this->_id_sujeito;
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