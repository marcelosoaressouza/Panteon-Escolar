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

class UsuarioXTemaPanteonDB extends PanteonEscolarBaseDBAccess
{
    protected $_nome_tabela = "usuario_x_tema_panteon";
    protected $_nome_tabela_primaria = "usuario";
    protected $_nome_tabela_secundaria = "tema_panteon";
    protected $_nome_tabela_terciaria = "permissao";

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

        $sql .= " INNER JOIN ".$this->_nome_tabela_terciaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_terciaria;
        $sql .= " = ".$this->_nome_tabela_terciaria.".id_".$this->_nome_tabela_terciaria;

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

        $model = new UsuarioXTemaPanteonModel();
        $model->bindIterator($it);

        return $model;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsUsuariosPorIDTemaPanteon($id, $max = 5)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;

        $sql .= " INNER JOIN ".$this->_nome_tabela_primaria;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
        $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

        $sql .= " INNER JOIN instituicao ";
        $sql .= " ON usuario.id_instituicao ";
        $sql .= " = instituicao.id_instituicao";

        // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
        $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria." = ".$id;
        $sql .= " ORDER BY data_cadastro_usuario DESC";
        $sql .= " LIMIT 0,".$max;




        $it = $this->getIterator($sql);

        return $it;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsUsuariosColetaramTemaPanteonPorIDTemaPanteon($id)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;

        $sql .= " INNER JOIN ".$this->_nome_tabela_primaria;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
        $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

        $sql .= " INNER JOIN instituicao ";
        $sql .= " ON usuario.id_instituicao ";
        $sql .= " = instituicao.id_instituicao";

        // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
        $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria." = ".$id;
        $sql .= " ORDER BY nome_completo_usuario DESC";




        $it = $this->getIterator($sql);

        return $it;

    }

    /**
    * @param int $idTemaPanteon
    * @param int $idInstituicao
    * @access public
    * @return IIterator
    */
    public function obterTodosOsUsuariosColetaramTemaPanteonPorIDTemaPanteonEPorIdInstituicao($idTemaPanteon, $idInstituicao)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;

        $sql .= " INNER JOIN ".$this->_nome_tabela_primaria;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_primaria;
        $sql .= " = ".$this->_nome_tabela_primaria.".id_".$this->_nome_tabela_primaria;

        $sql .= " INNER JOIN instituicao ";
        $sql .= " ON usuario.id_instituicao ";
        $sql .= " = instituicao.id_instituicao";

        // Mudar Esta Parte para Consultar na Tabela Primaria ou Secundaria
        $sql .= " WHERE ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria." = ".$idTemaPanteon;
        $sql .= " AND ".$this->_nome_tabela_primaria.".id_instituicao = ".$idInstituicao;
        $sql .= " ORDER BY nome_completo_usuario DESC";



        $it = $this->getIterator($sql);

        return $it;

    }

    /**
     * @param int $id
     * @access public
     * @return IIterator
    */
    public function obterTodosOsTemasPanteonPorIDUsuario($id)
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

        $sql .= " INNER JOIN ".$this->_nome_tabela_secundaria ;
        $sql .= " ON ".$this->_nome_tabela.".id_".$this->_nome_tabela_secundaria;
        $sql .= " = ".$this->_nome_tabela_secundaria.".id_".$this->_nome_tabela_secundaria;

        $it = $this->getIterator($sql);

        return $it;

    }

    public function cadastrar(UsuarioXTemaPanteonModel $usuarioxtemapanteon)
    {
        $sql = " INSERT INTO ";
        $sql .= $this->_nome_tabela;

        // Inicio Campos Tabela a serem Inseridas

        $sql .= " ( ";
        $sql .= "id_usuario, ";
        $sql .= "id_tema_panteon, ";
        $sql .= "id_permissao";
        $sql .= " ) ";

        // Fim Campos Tabela a serem Inseridas

        $sql .= " VALUES ";

        // Inicio Valores a serem Inseridas

        $sql .= " ( ";
        $sql .= "'".$usuarioxtemapanteon->getIDUsuario()."', ";
        $sql .= "'".$usuarioxtemapanteon->getIDTemaPanteon()."', ";
        $sql .= "'".$usuarioxtemapanteon->getIDPermissao()."'";
        $sql .= " ) ";

        // Fim Valores a serem Inseridas

        $this->executeSQL($sql);

    }

    public function verDuplicado(UsuarioXTemaPanteonModel $usuarioxtemapanteon)
    {
        $sql = "SELECT * FROM ";
        $sql .= $this->_nome_tabela;
        $sql .= " WHERE ";
        $sql .= "id_".$this->_nome_tabela_primaria." = ".$usuarioxtemapanteon->getIDUsuario();
        $sql .= " AND ";
        $sql .= "id_".$this->_nome_tabela_secundaria." = ".$usuarioxtemapanteon->getIDTemaPanteon();

        $it = $this->getIterator($sql, $param);

        return $it->Count();

    }

    public function obterIDPorIDUsuarioXIDTemaPanteon($id_usuario, $id_tema_panteon)
    {
        $sql = "SELECT id_usuario_x_tema_panteon FROM ";
        $sql .= $this->_nome_tabela;
        $sql .= " WHERE ";
        $sql .= "id_".$this->_nome_tabela_primaria." = ".$id_usuario;
        $sql .= " AND ";
        $sql .= "id_".$this->_nome_tabela_secundaria." = ".$id_tema_panteon;

        $it = $this->getIterator($sql);

        if($it->hasNext())
        {
            $sr = $it->moveNext();
            $id = $sr->getField("id_usuario_x_tema_panteon");
        }

        return $id;
    }

    public function excluir($id_usuario_x_tema_panteon)
    {
        $sql = "DELETE FROM ".$this->_nome_tabela." WHERE id_usuario_x_tema_panteon = ".$id_usuario_x_tema_panteon;

        $resultado = $this->executeSQL($sql);

        return $resultado;

    }

}

?>