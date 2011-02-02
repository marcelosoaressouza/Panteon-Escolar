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

class UsuarioXNivelAcessoDB extends PanteonEscolarBaseDBAccess
{
  protected $_nome_tabela = "usuario_x_nivel_acesso";
  protected $_nome_tabela_primaria = "usuario";

  public function obterTodosRelacionados()
  {

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
  public function obterPorId($id)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_".$this->_nome_tabela." = [[id]] ";

    $param = array("id" => $id);

    $it = $this->getIterator($sql, $param);

    $model = new UsuarioXNivelAcessoModel();
    $model->bindIterator($it);

    return $model;

  }

  /**
   * @param int $id
   * @access public
   * @return string
  */
  public function obterNivelAcessoPorIDUsuario($id)
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
    $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

    // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
    $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria." = ".$id;
    $sql .= " AND nome_usuario_x_nivel_acesso = 'roles'";

    $it = $this->getIterator($sql);

    if($it->hasNext())
    {
      $sr = $it->moveNext();
      $nivel_acesso = $sr->getField("valor_usuario_x_nivel_acesso");
    }

    return $nivel_acesso;

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

  public function cadastrarAnalista($email)
  {

    $db = new UsuarioDB($this->_context);
    $id_usuario = $db->obterIDPorEMail($email);

    $sql = " INSERT INTO ";
    $sql .= $this->_nome_tabela;

    // Inicio Campos Tabela a serem Inseridas

    $sql .= " ( ";
    $sql .= "nome_usuario_x_nivel_acesso , ";
    $sql .= "valor_usuario_x_nivel_acesso , ";
    $sql .= "id_usuario ";
    $sql .= " ) ";

    // Fim Campos Tabela a serem Inseridas

    $sql .= " VALUES ";

    // Inicio Valores a serem Inseridas

    $sql .= " ( ";
    $sql .= "'roles', ";
    $sql .= "'ANALISTA', ";
    $sql .= "'".$id_usuario."' ";
    $sql .= " ) ";

    // Fim Valores a serem Inseridas

    $this->executeSQL($sql);
  }

}

?>