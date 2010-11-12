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

class PerfilDB extends PanteonEscolarBaseDBAccess
{
  protected $_nome_tabela = "perfil";
  protected $_nome_tabela_primaria = "usuario";

  /**
   * @access public
   * @return IIterator
   */
  public function obterTodosRelacionados() {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
    $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

    $it = $this->getIterator($sql);

    return $it;

  }

  /**
   * @param int $id
   * @access public
   * @return Model
  */
  public function obterPorId($id) {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_".$this->_nome_tabela." = ".$id;

    $it = $this->getIterator($sql, $param);

    $model = new PerfilModel();
    $model->bindIterator($it);

    return $model;

  }

  /**
     * @param int $id
     * @access public
     * @return Model
    */
  public function obterPorIDUsuarioIDPerfil($id_usuario) {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_usuario = ".$id_usuario;

    $it = $this->getIterator($sql);

    while($it->hasNext()) {
      $sr = $it->moveNext();
      $id = $sr->getField("id_perfil");
    }

    return $id;

  }

  /**
   * @param int $id
   * @access public
   * @return Model
  */
  public function obterPorIdUsuario($id) {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_".$this->_nome_tabela_primaria." = ".$id;

    $it = $this->getIterator($sql, $param);

    $model = new PerfilModel();
    $model->bindIterator($it);

    return $model;

  }

  /**
   * @access public
   * @return IIterator
  */
  public function obterTodos() {
    $sql  = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $it = $this->getIterator($sql);

    return $it;

  }

  /**
   * @access public
   * @return IIterator
  */
  public function obterEstados() {
    $sql  = "SELECT * FROM estado ORDER BY nome ASC";

    $it = $this->getIterator($sql);

    return $it;

  }

  /**
   * @access public
   * @return IIterator
  */
  public function obterCidades() {
    $sql  = "SELECT * FROM cidade ORDER BY nome ASC";

    $it = $this->getIterator($sql);

    return $it;

  }

  /**
   * @access public
   * @return IIterator
  */
  public function obterCidadesPorUFEstado($uf) {
    $sql  = "SELECT * FROM cidade WHERE uf = '".$uf."'";
    $it = $this->getIterator($sql);

    return $it;

  }
  public function obterCidadePorIDCidade($id_cidade) {
    $cidade = "Não definida";
    $sql  = "SELECT * FROM cidade WHERE id_cidade = '".$id_cidade."'";
    $it = $this->getIterator($sql);

    if($it->hasNext()) {
      $sr = $it->moveNext();
      $cidade = $sr->getField("nome");
    }

    return $cidade;

  }

}

?>