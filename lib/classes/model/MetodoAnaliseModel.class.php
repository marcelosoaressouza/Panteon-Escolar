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

class MetodoAnaliseModel extends PanteonEscolarBaseModel
{
  private $_nome_metodo_analise;
  private $_descricao_metodo_analise;
  private $_data_hora_cadastro_metodo_analise;
  private $_caminho_foto_metodo_analise;

  private $_id_metodo_analise;
  private $_id_usuario;

  public function getIDMetodoAnalise()
  {
    return $this->_id_metodo_analise;
  }

  public function setIDMetodoAnalise($id_metodo_analise)
  {
    $this->_id_metodo_analise = $id_metodo_analise;
  }

  public function getNomeMetodoAnalise()
  {
    return $this->_nome_metodo_analise;
  }

  public function setNomeMetodoAnalise($nome_metodo_analise)
  {
    $this->_nome_metodo_analise = $nome_metodo_analise;
  }

  public function getDataHoraCadastroMetodoAnalise()
  {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraCadastroMetodoAnalise($data_hora_cadastro_metodo_analise)
  {
    $this->_data_hora_cadastro_metodo_analise = date("Y-m-d H:i:s");
  }

  public function getDescricaoMetodoAnalise()
  {
    return $this->_descricao_metodo_analise;
  }

  public function setDescricaoMetodoAnalise($descricao_metodo_analise)
  {
    $this->_descricao_metodo_analise = $descricao_metodo_analise;
  }

  public function getCaminhoFotoMetodoAnalise()
  {
    return $this->_caminho_foto_metodo_analise;
  }

  public function setCaminhoFotoMetodoAnalise($caminho_foto_metodo_analise)
  {
    $this->_caminho_foto_metodo_analise = $caminho_foto_metodo_analise;
  }

  public function getIDUsuario()
  {
    return $this->_id_usuario;
  }

  public function setIDUsuario($id_usuario)
  {
    $this->_id_usuario = $id_usuario;
  }
}

?>
