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

class UsuarioDB extends PanteonEscolarBaseDBAccess
{
  protected $_nome_tabela = "usuario";
  protected $_nome_tabela_primaria = "instituicao";
  protected $_nome_tabela_secundaria = "nivel_acesso";
  protected $_nome_tabela_terciaria = "usuario_x_turma";

  /**
   * @param void
   * @access public
   * @return Model
  */
  public function obterUltimoID()
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_".$this->_nome_tabela." = [[id]] ";

    $param = array("id" => $id);

    $it = $this->getIterator($sql, $param);

    $model = new UsuarioModel();
    $model->bindIterator($it);

    return $model;
  }

  /**
   * @access public
   * @return IIterator
   */
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
   * @return IIterator
   */
  public function obterTodosOsUsuariosPorIDInstituicao($id)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
    $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

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
  public function obterTodosOsUsuariosQueNaoEstaoNaTurmaPorIDInstituicao($id, $id_turma)
  {


    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
    $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

    //$sql .= " INNER JOIN ".$this->_nome_tabela_terciaria;
    //$sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela;
    //$sql .= " = ".$this->_nome_tabela_terciaria.".id_".$this->_nome_tabela;

    // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
    $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria." = ".$id;
    $sql .= " AND ".$this->_nome_tabela.".id_".$this->_nome_tabela;
    $sql .= " NOT IN ( SELECT id_".$this->_nome_tabela." FROM ".$this->_nome_tabela_terciaria." WHERE id_turma =".$id_turma.") ";

    $it = $this->getIterator($sql);

    return $it;

  }

  /**
   * @param int $id
   * @access public
   * @return IIterator
   */
  public function obterTodosOsUsuariosPorIDNivelAcesso($id)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_secundaria;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria;
    $sql .= " = ".$this->_nome_tabela_secundaria.".id_".$this->_nome_tabela_secundaria;

    // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
    $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria." = ".$id;



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

    $model = new UsuarioModel();
    $model->bindIterator($it);

    return $model;
  }

  /**
   * @param string $email
   * @access public
   * @return int
  */
  public function obterIDPorEMail($email)
  {
    $sql = "SELECT id_usuario FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "email_usuario  = '".$email."'";

    $it = $this->getIterator($sql);

    while($it->hasNext())
    {
      $sr = $it->moveNext();
      $id_usuario = $sr->getField("id_usuario");
    }

    return $id_usuario;

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

  public function excluir($id)
  {
    $sql = "DELETE FROM ".$this->_nome_tabela." WHERE id_usuario = ".$id;

    $resultado = $this->executeSQL($sql);

    return $resultado;

  }

}

?>
