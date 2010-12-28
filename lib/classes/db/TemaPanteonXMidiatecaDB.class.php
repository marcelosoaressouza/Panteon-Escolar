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

class TemaPanteonXMidiatecaDB extends PanteonEscolarBaseDBAccess
{
    protected $_nome_tabela = "tema_panteon_x_midiateca";
    protected $_nome_tabela_primaria = "tema_panteon";
    protected $_nome_tabela_secundaria = "midiateca";

    public function obterTodosRelacionados($id_tema_panteon = "")
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;

        $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
        $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

        $sql .= " INNER JOIN ".$this->_nome_tabela_secundaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria;
        $sql .= " = ".$this->_nome_tabela_secundaria.".id_".$this->_nome_tabela_secundaria;

        if($id_tema_panteon !="")
        {
            $sql .= " WHERE id_".$this->_nome_tabela." = ".$id_tema_panteon;
        }

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

        $model = new TemaPanteonXMidiatecaDB();
        $model->bindIterator($it);

        return $model;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsTemasPanteonPorIDMidiateca($id)
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
    public function obterTodasAsMidiatecasPorIDTemaPanteon($id)
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

    public function cadastrar(TemaPanteonXMidiatecaModel $temapanteonxmidiateca)
    {
        $sql = " INSERT INTO ";
        $sql .= $this->_nome_tabela;

        // Inicio Campos Tabela a serem Inseridas

        $sql .= " ( ";
        $sql .= "id_tema_panteon, ";
        $sql .= "id_midiateca";
        $sql .= " ) ";

        // Fim Campos Tabela a serem Inseridas

        $sql .= " VALUES ";

        // Inicio Valores a serem Inseridas

        $sql .= " ( ";
        $sql .= "'".$temapanteonxmidiateca->getIDTemaPanteon()."', ";
        $sql .= "'".$temapanteonxmidiateca->getIDMidiateca()."'";
        $sql .= " ) ";

        // Fim Valores a serem Inseridas

        $this->executeSQL($sql);
    }

    public function verDuplicado(TemaPanteonXMidiatecaModel $temapanteonxmidiateca)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;
        $sql .= " WHERE ";
        $sql .= "id_".$this->_nome_tabela_primaria." = ".$temapanteonxmidiateca->getIDTemaPanteon();
        $sql .= " AND ";
        $sql .= "id_".$this->_nome_tabela_secundaria." = ".$temapanteonxmidiateca->getIDMidiateca();

        $it = $this->getIterator($sql, $param);

        return $it->Count();

    }
}

?>