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

class UsuarioXTemaPanteonModel extends PanteonEscolarBaseModel
{
    private $_id_usuario;
    private $_id_tema_panteon;
    private $_id_permissao;

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

    public function setIDPermissao($id_permissao)
    {
        $this->_id_permissao = $id_permissao;
    }

    public function getIDPermissao()
    {
        return $this->_id_permissao;
    }

}

?>