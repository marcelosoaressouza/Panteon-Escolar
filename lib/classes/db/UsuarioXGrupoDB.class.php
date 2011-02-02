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

class UsuarioXGrupoDB extends PanteonEscolarBaseDBAccess
{
  protected $_nome_tabela = "usuario_x_grupo";
  protected $_nome_tabela_primaria = "grupo";
  protected $_nome_tabela_secundaria = "usuario";

  public function obterTodosRelacionados()
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
    $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

    $sql .= " INNER JOIN ".$this->_nome_tabela_secundaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria;
    $sql .= " = ".$this->_nome_tabela_secundaria.".id_".$this->_nome_tabela_secundaria;

    $it = $this->getIterator($sql);

    return $it;

  }

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

    $model = new UsuarioXGrupoModel();
    $model->bindIterator($it);

    return $model;

  }

  /**
   * @param int $id
   * @access public
   * @return IIterator
  */
  public function obterTodosOsUsuariosPorIDGrupo($id)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
    $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

    $sql .= " INNER JOIN ".$this->_nome_tabela_secundaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria;
    $sql .= " = ".$this->_nome_tabela_secundaria.".id_".$this->_nome_tabela_secundaria;

    // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
    $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria." = ".$id;

    $it = $this->getIterator($sql);

    return $it;

  }

  /**
   * @param int $id
   * @access public
   * @return IIterator
  */
  public function obterTodosOsGruposPorIDUsuario($id)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
    $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

    $sql .= " INNER JOIN ".$this->_nome_tabela_secundaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria;
    $sql .= " = ".$this->_nome_tabela_secundaria.".id_".$this->_nome_tabela_secundaria;

    // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
    $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria." = ".$id;

    $it = $this->getIterator($sql);

    return $it;

  }

  /**
   * @param int $id_usuario
   * @param int $id_tema_panteon
   * @access public
   * @return int
  */
  public function obterIDGrupoPorIDUsuarioXIDTemaPanteon($id_usuario, $id_tema_panteon)
  {
    $sql = "SELECT * FROM grupo INNER JOIN usuario_x_grupo ON grupo.id_grupo = usuario_x_grupo.id_grupo WHERE grupo.id_tema_panteon = ".$id_tema_panteon;
    $sql .= " AND usuario_x_grupo.id_usuario = ".$id_usuario;
    $it = PanteonEscolarBaseDBAccess::getIterator($sql);

    while($it->hasNext())
    {
      $sr = $it->moveNext();
      $id_grupo = $sr->getField("id_grupo");
    }

    return $id_grupo;

  }

  /**
   * @access public
   * @return IIterator
  */
  public function obterTodos()
  {
    $sql  = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $it = $this->getIterator($sql);

    return $it;

  }

  /**
  * @param int $idTemaPanteon
  * @access public
  * @return IIterator
  */
  public function obterTodosGruposPorIdTemaPanteon($idTemaPanteon)
  {
    $sql = "SELECT * FROM ".$this->_nome_tabela_primaria;

    $sql .= " WHERE ".$this->_nome_tabela_primaria.".id_tema_panteon"." = ".$idTemaPanteon;

    $it = $this->getIterator($sql);

    return $it;
  }

}

?>