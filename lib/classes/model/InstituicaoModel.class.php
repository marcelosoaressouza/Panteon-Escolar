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

class InstituicaoModel extends PanteonEscolarBaseModel
{
    private $_nome_instituicao;
    private $_descricao_instituicao;

    public function getNomeInstituicao()
    {
        return $this->_nome_instituicao;
    }

    public function setNomeInstituicao($nome_instituicao)
    {
        $this->_nome_instituicao = $nome_instituicao;
    }

    public function getDescricaoInstituicao()
    {
        return $this->_descricao_instituicao;
    }

    public function setDescricaoInstituicao($descricao_instituicao)
    {
        $this->_descricao_instituicao = $descricao_instituicao;
    }

}

?>
