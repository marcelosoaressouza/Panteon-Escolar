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

class TagDB extends PanteonEscolarBaseDBAccess
{
    protected $_nome_tabela = "tag";

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

        $model = new TagModel();
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

    public function cadastrar(TagModel $tag)
    {
        $sql = " INSERT INTO ";
        $sql .= $this->_nome_tabela;

        // Inicio Campos Tabela a serem Inseridas

        $sql .= " ( ";
        $sql .= "nome_tag";
        $sql .= " ) ";

        // Fim Campos Tabela a serem Inseridas

        $sql .= " VALUES ";

        // Inicio Valores a serem Inseridas

        $sql .= " ( ";
        $sql .= "'".$tag->getNomeTag()."'";
        $sql .= " ) ";

        // Fim Valores a serem Inseridas

        $this->executeSQL($sql);

    }

    public function verDuplicado($tag)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;
        $sql .= " WHERE ";
        $sql .= " nome_tag = '".trim(strtolower($tag))."'";

        $it = $this->getIterator($sql);

        return $it->Count();

    }

    public function excluir($id)
    {
        $sql = "DELETE FROM tag WHERE id_tag = ".$id;

        $resultado = $this->executeSQL($sql);

        return $resultado;

    }
}

?>
