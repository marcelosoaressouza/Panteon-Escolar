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

class ForumDB extends PanteonEscolarBaseDBAccess
{
    protected $_nome_tabela = "forum";
    protected $_nome_tabela_primaria = "tema_panteon";

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

        $model = new ForumModel();
        $model->bindIterator($it);

        return $model;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
     */
    public function obterForumPorIDTemaPanteon($id)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;

        $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
        $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

        // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
        $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria." = ".$id;



        $it = $this->getIterator($sql);

        if($it->hasNext())
        {
            $sr = $it->moveNext();
            $id_forum = $sr->getField("id_forum");
        }

        return $id_forum;

    }

    /**
     * @access public
     * @return IIterator
    */
    public function obterTodos()
    {
        $sql  = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;

        $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
        $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

        $it = $this->getIterator($sql);

        return $it;

    }

    public function cadastrar(ForumModel $forum)
    {
        $sql = " INSERT INTO ";
        $sql .= $this->_nome_tabela;
        $sql .= " ( id_tema_panteon ) VALUES ('".$forum->getIDTemaPanteon()."')";

        $this->executeSQL($sql);

    }

    public function verDuplicado($id_tema_panteon)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;
        $sql .= " WHERE ";
        $sql .= " id_tema_panteon = ".$id_tema_panteon;

        $it = $this->getIterator($sql);

        return $it->Count();

    }
}

?>