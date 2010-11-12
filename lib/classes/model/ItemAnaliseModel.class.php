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

class ItemAnaliseModel extends PanteonEscolarBaseModel
{
  private $_nome_item_analise;
  private $_descricao_item_analise;

  private $_id_metodo_analise;

  public function getNomeItemAnalise() {
    return $this->_nome_item_analise;
  }

  public function setNomeItemAnalise($nome_item_analise) {
    $this->_nome_item_analise = $nome_item_analise;
  }

  public function getDescricaoItemAnalise() {
    return $this->_descricao_item_analise;
  }

  public function setDescricaoItemAnalise($descricao_item_analise) {
    $this->_descricao_item_analise = $descricao_item_analise;
  }

  public function getIDMetodoAnalise() {
    return $this->_id_metodo_analise;
  }

  public function setIDMetodoAnalise($id_metodo_analise) {
    $this->_id_metodo_analise = $id_metodo_analise;
  }

}

?>
