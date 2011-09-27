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

class CidadeDB extends PanteonEscolarBaseDBAccess
{
  protected $_nome_tabela = "cidade";

  /**
   * @param int $id
   * @access public
   * @return Model
  */
  public function obterPorId($id)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_".$this->_nome_tabela." = [[id]] ";

    $param = array("id" => $id);

    $it = $this->getIterator($sql, $param);

    $model = new CidadeModel();
    $model->bindIterator($it);

    return $model;
  }

  /**
  * @access public
  * @return IIterator
  */
  public function obterCidadesPorUFEstado($uf)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "uf = '".$uf."'";
    $it = $this->getIterator($sql);

    return $it;

  }

  /**
   * @param int $id
   * @access public
   * @return Model
  */
  public function obterPorCidadeId($id)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_".$this->_nome_tabela." = $id ";

    //$param = array("id" => $id);

    $it = $this->getIterator($sql);

    //$model = new CidadeModel();
    //$model->bindIterator($it);

    return $it;
  }

  /**
   * @access public
   * @return IIterator
   */
  public function obterTodos()
  {
    $sql = "SELECT * FROM ".$this->_nome_tabela;

    $it = $this->getIterator($sql);

    return $it;
  }


}

?>