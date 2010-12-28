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

class MuralModel extends PanteonEscolarBaseModel
{
    private $_nome_mural;
    private $_texto_mural;
    private $_data_hora_cadastro_mural;

    private $_id_usuario;
    private $_id_tema_panteon;

    public function getNomeMural()
    {
        return $this->_nome_mural;
    }

    public function setNomeMural($nome_mural)
    {
        $this->_nome_mural = $nome_mural;
    }

    public function getTextoMural()
    {
        return $this->_texto_mural;
    }

    public function setTextoMural($texto_mural)
    {
        $this->_texto_mural = $texto_mural;
    }

    public function getDataHoraCadastroMural()
    {
        return date("Y-m-d H:i:s");
    }

    public function setDataHoraCadastroMural($data_hora_cadastro_mural)
    {
        $this->_data_hora_cadastro_mural = date("Y-m-d H:i:s");
    }

    public function setIDUsuario($id_usuario)
    {
        $this->_id_usuario = $id_usuario;
    }

    public function getIDUsuario()
    {
        return $this->_id_usuario;
    }

    public function setIDTemaPanteon($id_tema_panteon)
    {
        $this->_id_tema_panteon = $id_tema_panteon;
    }

    public function getIDTemaPanteon()
    {
        return $this->_id_tema_panteon;
    }

}

?>
