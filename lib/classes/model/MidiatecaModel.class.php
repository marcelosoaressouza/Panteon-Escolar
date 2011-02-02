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

class MidiatecaModel extends PanteonEscolarBaseModel
{
  private $_nome_midiateca;
  private $_descricao_midiateca;
  private $_data_hora_cadastro_midiateca;
  private $_caminho_arquivo_midiateca;
  private $_url_midiateca;

  private $_id_tipo_midia;

  public function getNomeMidiateca()
  {
    return $this->_nome_midiateca;
  }

  public function setNomeMidiateca($nome_midiateca)
  {
    $this->_nome_midiateca = $nome_midiateca;
  }

  public function getCaminhoArquivoMidiateca()
  {
    return $this->_caminho_arquivo_midiateca;
  }

  public function setCaminhoArquivoMidiateca($caminho_arquivo_midiateca)
  {
    $this->_caminho_arquivo_midiateca = $caminho_arquivo_midiateca;
  }

  public function getDataHoraCadastroMidiateca()
  {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroMidiateca($data_hora_cadastro_midiateca)
  {
    $this->_data_hora_cadastro_midiateca = date("Y-m-d H:i:s");
  }

  public function getDescricaoMidiateca()
  {
    return $this->_descricao_midiateca;
  }

  public function setDescricaoMidiateca($descricao_midiateca)
  {
    $this->_descricao_midiateca = $descricao_midiateca;
  }

  public function getURLMidiateca()
  {
    return $this->_url_midiateca;
  }

  public function setURLMidiateca($url_midiateca)
  {
    $this->_url_midiateca = $url_midiateca;
  }

  public function getIDTipoMidia()
  {
    return $this->_id_tipo_midia;
  }

  public function setIDTipoMidia($id_tipo_midia)
  {
    $this->_id_tipo_midia = $id_tipo_midia;
  }

}

?>
