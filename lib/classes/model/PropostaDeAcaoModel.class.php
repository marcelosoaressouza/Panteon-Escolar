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

class PropostaDeAcaoModel extends PanteonEscolarBaseModel
{
    private $_nome_proposta_de_acao;
    private $_texto_proposta_de_acao;
    private $_data_cadastro_proposta_de_acao;
    private $_id_diagnostico_individual;
    private $_id_usuario;

    public function getNomePropostaDeAcao()
    {
        return $this->_nome_proposta_de_acao;
    }
    public function getTextoPropostaDeAcao()
    {
        return $this->_texto_proposta_de_acao;
    }
    public function getDataCadastroPropostaDeAcao()
    {
        return date("Y-m-d");
    }
    public function getIDDiagnosticoIndividual()
    {
        return $this->_id_diagnostico_individual;
    }
    public function getIDUsuario()
    {
        return $this->_id_usuario;
    }

    public function setNomePropostaDeAcao($_nome_proposta_de_acao)
    {
        $this->_nome_proposta_de_acao = $_nome_proposta_de_acao;
    }
    public function setTextoPropostaDeAcao($_texto_proposta_de_acao)
    {
        $this->_texto_proposta_de_acao = $_texto_proposta_de_acao;
    }
    public function setDataCadastroPropostaDeAcao($_data_cadastro_proposta_de_acao)
    {
        $this->_data_cadastro_proposta_de_acao = date("Y-m-d");
    }
    public function setIDDiagnosticoIndividual($_id_diagnostico_individual)
    {
        $this->_id_diagnostico_individual = $_id_diagnostico_individual;
    }
    public function setIDUsuario($_id_usuario)
    {
        $this->_id_usuario = $_id_usuario;
    }

}

?>