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

class TipoMidiaModel extends PanteonEscolarBaseModel
{
  private $_nome_tipo_midia;
  private $_caminho_foto_tipo_midia;

  public function getNomeTipoMidia()
  {
    return $this->_nome_tipo_midia;
  }

  public function setNomeTipoMidia($nome_tipo_midia)
  {
    $this->_nome_tipo_midia = $nome_tipo_midia;
  }

  public function getCaminhoFotoTipoMidia()
  {
    return $this->_caminho_foto_tipo_midia;
  }

  public function setCaminhoFotoTipoMidia($caminho_foto_tipo_midia)
  {
    $this->_caminho_foto_tipo_midia = $caminho_foto_tipo_midia;
  }

}

?>