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

class PerfilModel extends PanteonEscolarBaseModel
{
  private $_texto_perfil;
  private $_data_hora_atualizacao_perfil;
  private $_data_nascimento_perfil;
  private $_caminho_foto_perfil;
  private $_uf_estado;
  private $_id_cidade;

  private $_id_usuario;

  public function getTextoPerfil()
  {
    return $this->_texto_perfil;
  }

  public function setTextoPerfil($texto_perfil)
  {
    $this->_texto_perfil = $texto_perfil;
  }

  public function getDataHoraAtualizacaoPerfil()
  {
    return date("Y-m-d H:i:s");
  }

  public function setDataHoraAtualizacaoPerfil($data_hora_atualizacao_perfil)
  {
    $this->_data_hora_atualizacao_perfil = date("Y-m-d H:i:s");
  }

  public function setIDUsuario($id_usuario)
  {
    $this->_id_usuario = $id_usuario;
  }

  public function getIDUsuario()
  {
    return $this->_id_usuario;
  }

  public function setDataNascimentoPerfil($data_nascimento_perfil)
  {
    $this->_data_nascimento_perfil = $data_nascimento_perfil;
  }

  public function getDataNascimentoPerfil()
  {
    return $this->_data_nascimento_perfil;
  }

  public function setCaminhoFotoPerfil($caminho_foto_perfil)
  {
    $this->_caminho_foto_perfil = $caminho_foto_perfil;
  }

  public function getCaminhoFotoPerfil()
  {
    return $this->_caminho_foto_perfil;
  }

  public function setIDCidade($id_cidade)
  {
    $this->_id_cidade = $id_cidade;
  }

  public function getIDCidade()
  {
    return $this->_id_cidade;
  }

  public function setUFEstado($uf_estado)
  {
    $this->_uf_estado = $uf_estado;
  }

  public function getUFEstado()
  {
    return $this->_uf_estado;
  }

}

?>
