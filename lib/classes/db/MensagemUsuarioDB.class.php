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

class MensagemUsuarioDB extends PanteonEscolarBaseDBAccess
{
  protected $_nome_tabela = "mensagem_usuario";
  protected $_nome_tabela_primaria = "mensagem_usuario";
  protected $_nome_tabela_secundaria = "tema_panteon";

  /**
   * @access public
   * @return IIterator
   */
  public function obterTodosRelacionados()
  {
    $sql = "SELECT * FROM ";
    $sql .= $this->_nome_tabela;

    $sql .= " INNER JOIN ".$this->_nome_tabela_primaria." AS mur ";
    $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
    $sql .= " = mur.id_".$this->_nome_tabela_primaria;

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
  public function obterTodasAsMensagensUsuarioPorIDMensagemResposta($id)
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
  public function obterTodasAsMensagensUsuarioPorIDTemaPanteon($id)
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

    $model = new MensagemUsuarioModel();
    $model->bindIterator($it);

    return $model;
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

  /**
   * @method Metodo para obter a ultima mensagem inserida pelo usuario
   * @param int $id_usuario_orig
   * @return MensagemUsuarioModel
   */
  public function obterUtilmaMensagemUsuario($id_usuario_orig)
  {
    $sql .= " SELECT * FROM ".$this->_nome_tabela;
    $sql .= " WHERE  id_".$this->_nome_tabela_primaria." = (SELECT MAX(".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria.") FROM ".$this->_nome_tabela;
    $sql .= " WHERE id_usuario_orig = [[id_usuario_orig]] )";

    $param = array("id_usuario_orig" => $id_usuario_orig);

    $it = $this->getIterator($sql, $param);

    $model = new MensagemUsuarioModel();
    $model->bindIterator($it);

    return $model;
  }

  /**
  * @method Metodo para obter a ultima mensagem inserida pelo usuario
  * @param int $id_usuario_dest
  * @return MensagemUsuarioModel
  */
  public function obterUltimaMensagemUsuarioRecebida($id_usuario_dest)
  {
    $sql = "SELECT * FROM ".$this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_usuario_dest = [[id_usuario_dest]] ";

    $param = array("id_usuario_dest" => $id_usuario_dest);

    $it = $this->getIterator($sql, $param);

    return $it;
  }

  /**
  * @method Metodo para obter a ultima mensagem inserida pelo usuario
  * @param int $id_usuario_dest
  * @return MensagemUsuarioModel
  */
  public function obterMensagensNaoLidas($id_usuario_dest)
  {
    $sql = "SELECT * FROM ".$this->_nome_tabela;
    $sql .= " WHERE ";
    $sql .= "id_usuario_dest = [[id_usuario_dest]] ";
    $sql .= " AND ";
    $sql .= " lida IS NULL";

    $param = array("id_usuario_dest" => $id_usuario_dest);

    $it = $this->getIterator($sql, $param);

    return $it;
  }

  /**
   * @method Metodo para obter a ultima mensagem inserida pelo usuario
   * @author Roberto Rander rrander at gmail.com
   * @param int $id_mensagem_usuario
   * @param string $id_usuario_dest
   * @return
   */
  public function atualizarDestinatarioMensagemUsuario($id_mensagem_usuario, $id_usuario_dest)
  {
    $sql = " UPDATE ".$this->_nome_tabela;
    $sql .= " SET id_usuario_dest = '".$id_usuario_dest."' ";
    $sql .= " WHERE id_".$this->_nome_tabela." = ".$id_mensagem_usuario." ";

    $resultado = $this->executeSQL($sql);

    return $resultado;
  }

  /**
  * @method Metodo para marcar mensagem como lida
  * @param int $id_mensagem_usuario
  * @return
  */
  public function atualizaMensagemLida($id_mensagem_usuario)
  {
    $sql = " UPDATE ".$this->_nome_tabela;
    $sql .= " SET lida = 1 ";
    $sql .= " WHERE id_".$this->_nome_tabela." = ".$id_mensagem_usuario." ";

    $resultado = $this->executeSQL($sql);

    return $resultado;
  }

  /**
   * @method Metodo para excluir mensagem recebida
   * @author Roberto Rander rrander at gmail.com
   * @param int $id_mensagem_usuario
   * @return type
   */
  public function excluirMensagemUsuarioRecebida($id_mensagem_usuario)
  {
    $sql = "DELETE FROM ".$this->_nome_tabela." WHERE id_".$this->_nome_tabela." = ".$id_mensagem_usuario;

    $resultado = $this->executeSQL($sql);

    return $resultado;
  }

}

?>
