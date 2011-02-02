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

class GrupoSocialModel extends PanteonEscolarBaseModel
{
  private $_nome_grupo_social;
  private $_descricao_grupo_social;

  private $_id_subgrupo_social;
  private $_id_estrutura_social;

  public function getNomeGrupoSocial()
  {
    return $this->_nome_grupo_social;
  }

  public function setNomeGrupoSocial($nome_grupo_social)
  {
    $this->_nome_grupo_social = $nome_grupo_social;
  }

  public function getDescricaoGrupoSocial()
  {
    return $this->_descricao_grupo_social;
  }

  public function setDescricaoGrupoSocial($descricao_grupo_social)
  {
    $this->_descricao_grupo_social = $descricao_grupo_social;
  }

  public function getIDSubGrupoSocial()
  {
    return $this->_id_subgrupo_social;
  }

  public function setIDSubGrupoSocial($id_sub_grupo_social)
  {
    $this->_id_subgrupo_social = $id_sub_grupo_social;
  }

  public function getIDEstruturaSocial()
  {
    return $this->_id_estrutura_social;
  }

  public function setIDEstruturaSocial($id_estrutura_social)
  {
    $this->_id_estrutura_social = $id_estrutura_social;
  }

}

?>
