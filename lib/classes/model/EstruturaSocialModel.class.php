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

class EstruturaSocialModel extends PanteonEscolarBaseModel
{
  private $_nome_estrutura_social;
  private $_descricao_estrutura_social;
  private $_caminho_foto_estrutura_social;

  public function getNomeEstruturaSocial()
  {
    return $this->_nome_estrutura_social;
  }

  public function setNomeEstruturaSocial($nome_estrutura_social)
  {
    $this->_nome_estrutura_social = $nome_estrutura_social;
  }

  public function getDescricaoEstruturaSocial()
  {
    return $this->_descricao_estrutura_social;
  }

  public function setDescricaoEstruturaSocial($caminho_foto_estrutura_social)
  {
    $this->_descricao_estrutura_social = $descricao_estrutura_social;
  }

  public function getCaminhoFotoEstruturaSocial()
  {
    return $this->_caminho_foto_estrutura_social;
  }

  public function setCaminhoFotoEstruturaSocial($caminho_foto_estrutura_social)
  {
    $this->_caminho_foto_estrutura_social = $caminho_foto_estrutura_social;
  }

}

?>
