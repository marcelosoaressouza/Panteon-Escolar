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

class PropostaDeAcaoGeralModel extends PanteonEscolarBaseModel
{
    private $_nome_proposta_de_acao_geral;
    private $_texto_proposta_de_acao_geral;
    private $_data_cadastro_proposta_de_acao_geral;
    private $_id_usuario;
    private $_id_tema_panteon;

    public function getNomePropostaDeAcaoGeral()
    {
        return $this->_nome_proposta_de_acao_geral;
    }

    public function getTextoPropostaDeAcaoGeral()
    {
        return $this->_texto_proposta_de_acao_geral;
    }

    public function getDataCadastroPropostaDeAcaoGeral()
    {
        return date("Y-m-d");
    }

    public function getIDUsuario()
    {
        return $this->_id_usuario;
    }

    public function getIDTemaPanteon()
    {
        return $this->_id_tema_panteon;

    }

    public function setNomePropostaDeAcaoGeral($_nome_proposta_de_acao_geral)
    {
        $this->_nome_proposta_de_acao_geral = $_nome_proposta_de_acao_geral;
    }

    public function setTextoPropostaDeAcaoGeral($_texto_proposta_de_acao_geral)
    {
        $this->_texto_proposta_de_acao_geral = $_texto_proposta_de_acao_geral;
    }

    public function setDataCadastroPropostaDeAcaoGeral($_data_cadastro_proposta_de_acao_geral)
    {
        $this->_data_cadastro_proposta_de_acao_geral = date("Y-m-d");
    }

    public function setIDUsuario($_id_usuario)
    {
        $this->_id_usuario = $_id_usuario;
    }

    public function setIDTemaPanteon($_tema_panteon)
    {
        $this->_id_tema_panteon = $_id_tema_panteon;

    }

}

?>