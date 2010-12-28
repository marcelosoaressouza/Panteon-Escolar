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

class TemaPanteonDB extends PanteonEscolarBaseDBAccess
{
    protected $_nome_tabela = "tema_panteon";
    protected $_nome_tabela_primaria = "metodo_analise";
    protected $_nome_tabela_secundaria = "estrutura_social";

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
    public function obterTodosOsTemasPanteonPorIDMetodoAnalise($id)
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
    public function obterTodosOsTemasPanteonPorIDEstruturaSocial($id)
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

        $model = new TemaPanteonModel();
        $model->bindIterator($it);

        return $model;
    }

    /**
     * @access public
     * @return IIterator
     */
    public function obterTodos($max = "")
    {
        $sql = "SELECT * FROM ".$this->_nome_tabela;

        if($max != "")
        {
            $sql.= " LIMIT ".$max;
        }

        $it = $this->getIterator($sql);

        return $it;
    }

    /**
     * @access public
     * @return IIterator
     */
    public function obterTodosPublicados($max = "")
    {
        $sql = "SELECT * FROM ".$this->_nome_tabela;
        $sql .= " WHERE publicado_tema_panteon = 1 ";

        if($max != "")
        {
            $sql.= " LIMIT ".$max;
        }

        $it = $this->getIterator($sql);

        return $it;
    }

}

?>
