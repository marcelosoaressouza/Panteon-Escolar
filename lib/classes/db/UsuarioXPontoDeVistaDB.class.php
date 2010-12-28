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

class UsuarioXPontoDeVistaDB extends PanteonEscolarBaseDBAccess
{
    protected $_nome_tabela = "usuario_x_ponto_de_vista";
    protected $_nome_tabela_primaria = "ponto_de_vista";
    protected $_nome_tabela_secundaria = "usuario";
    protected $_nome_tabela_terciaria = "tema_panteon";
    protected $_nome_tabela_auxiliar = "sujeito";

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

        $model = new UsuarioXPontoDeVistaModel();
        $model->bindIterator($it);

        return $model;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsUsuariosPorIDPontoDeVista($id)
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
     * @param int $id_usuario
     * @access public
     * @return IIterator
    */
    public function obterPorIDUsuarioXPontoDeVista($id_usuario, $id_ponto_de_vista)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;
        $sql .= " WHERE id_usuario = ".$id_usuario." AND id_ponto_de_vista = ".$id_ponto_de_vista;

        $it = $this->getIterator($sql);

        return $it;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsPontoDeVistaPorIDUsuario($id)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;

        $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
        $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

        $sql .= " INNER JOIN ".$this->_nome_tabela_secundaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria;
        $sql .= " = ".$this->_nome_tabela_secundaria.".id_".$this->_nome_tabela_secundaria;

        $sql .= " INNER JOIN ".$this->_nome_tabela_terciaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_terciaria;
        $sql .= " = ".$this->_nome_tabela_terciaria.".id_".$this->_nome_tabela_terciaria;

        // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
        $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria." = ".$id;

        $it = $this->getIterator($sql);

        return $it;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsPontoDeVistaPorIDTemaPanteonXIDUsuario($id_tema_panteon, $id_usuario, $max = 10, $coletados = "")
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;

        $sql .= " INNER JOIN ".$this->_nome_tabela_primaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
        $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

        $sql .= " INNER JOIN ".$this->_nome_tabela_secundaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria;
        $sql .= " = ".$this->_nome_tabela_secundaria.".id_".$this->_nome_tabela_secundaria;

        $sql .= " INNER JOIN ".$this->_nome_tabela_terciaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_terciaria;
        $sql .= " = ".$this->_nome_tabela_terciaria.".id_".$this->_nome_tabela_terciaria;

        $sql .= " INNER JOIN ".$this->_nome_tabela_auxiliar ;
        $sql .= " ON ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_auxiliar;
        $sql .= " = ".$this->_nome_tabela_auxiliar.".id_".$this->_nome_tabela_auxiliar;

        // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
        $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_terciaria." = ".$id_tema_panteon;
        $sql .= " AND ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria." = ".$id_usuario;

        if($coletados != "")
        {
            $sql .= " AND usuario_x_ponto_de_vista.coletado_usuario_x_ponto_de_vista = ".$coletados;
        }

        $sql .= " LIMIT ".$max;


        $it = $this->getIterator($sql);

        return $it;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsPontoDeVistaPorIDUsuarioXIDTemaPanteon($id_usuario, $id_tema_panteon)
    {
        $sql  = " SELECT * FROM `ponto_de_vista`, `sujeito`, `tema_panteon`, `usuario_x_ponto_de_vista` ";
        $sql .= " WHERE ";
        $sql .= " usuario_x_ponto_de_vista.id_ponto_de_vista = ponto_de_vista.id_ponto_de_vista ";
        $sql .= " AND ";
        $sql .= " ponto_de_vista.id_sujeito = sujeito.id_sujeito ";
        $sql .= " AND ";
        $sql .= " sujeito.id_tema_panteon = tema_panteon.id_tema_panteon ";
        $sql .= " AND ";
        $sql .= " tema_panteon.id_tema_panteon = ".$id_tema_panteon;
        $sql .= " AND ";
        $sql .= " usuario_x_ponto_de_vista.id_usuario = ".$id_usuario;

        $it = $this->getIterator($sql);

        return $it;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsPontoDeVistaPorIDUsuarioXIDTemaPanteonColetados($id_usuario, $id_tema_panteon, $coletados = "")
    {
        $sql  = " SELECT * FROM `ponto_de_vista`, `sujeito`, `tema_panteon`, `usuario_x_ponto_de_vista` ";
        $sql .= " WHERE ";
        $sql .= " usuario_x_ponto_de_vista.id_ponto_de_vista = ponto_de_vista.id_ponto_de_vista ";
        $sql .= " AND ";
        $sql .= " ponto_de_vista.id_sujeito = sujeito.id_sujeito ";
        $sql .= " AND ";
        $sql .= " sujeito.id_tema_panteon = tema_panteon.id_tema_panteon ";
        $sql .= " AND ";
        $sql .= " tema_panteon.id_tema_panteon = ".$id_tema_panteon;
        $sql .= " AND ";
        $sql .= " usuario_x_ponto_de_vista.id_usuario = ".$id_usuario;

        if($coletados != "")
        {
            $sql .= " AND usuario_x_ponto_de_vista.coletado_usuario_x_ponto_de_vista = ".$coletados;
        }

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

    public function cadastrar(UsuarioXPontoDeVistaModel $usuarioxpontodevista)
    {
        $sql = " INSERT INTO ";
        $sql .= $this->_nome_tabela;

        // Inicio Campos Tabela a serem Inseridas

        $sql .= " ( ";
        $sql .= "data_hora_usuario_x_ponto_de_vista, ";
        $sql .= "coletado_usuario_x_ponto_de_vista, ";
        $sql .= "texto_usuario_x_ponto_de_vista, ";
        $sql .= "id_usuario, ";
        $sql .= "id_tema_panteon, ";
        $sql .= "id_ponto_de_vista";
        $sql .= " ) ";

        // Fim Campos Tabela a serem Inseridas

        $sql .= " VALUES ";

        // Inicio Valores a serem Inseridas

        $sql .= " ( ";
        $sql .= "'".$usuarioxpontodevista->getDataHoraUsuarioXPontoDeVista()."', ";
        $sql .= "'".$usuarioxpontodevista->getColetadoUsuarioXPontoDeVista()."', ";
        $sql .= "'".$usuarioxpontodevista->getTextoUsuarioXPontoDeVista()."', ";
        $sql .= "'".$usuarioxpontodevista->getIDUsuario()."', ";
        $sql .= "'".$usuarioxpontodevista->getIDTemaPanteon()."', ";
        $sql .= "'".$usuarioxpontodevista->getIDPontodeVista()."'";
        $sql .= " ) ";

        // Fim Valores a serem Inseridas
        Debug::PrintValue($sql);
        $this->executeSQL($sql);
    }

    public function verDuplicado(UsuarioXPontoDeVistaModel $usuarioxpontodevista)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;
        $sql .= " WHERE ";
        $sql .= "id_".$this->_nome_tabela_primaria." = ".$usuarioxpontodevista->getIDPontodeVista();
        $sql .= " AND ";
        $sql .= "id_".$this->_nome_tabela_secundaria." = ".$usuarioxpontodevista->getIDUsuario();

        $it = $this->getIterator($sql, $param);

        return $it->Count();

    }

    public function excluir($id)
    {
        $sql = "DELETE FROM ".$this->_nome_tabela." WHERE id_usuario_x_ponto_de_vista = ".$id;

        $resultado = $this->executeSQL($sql);

        return $resultado;

    }

}

?>