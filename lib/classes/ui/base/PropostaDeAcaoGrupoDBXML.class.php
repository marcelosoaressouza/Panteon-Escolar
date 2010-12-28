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

class PropostaDeAcaoGrupoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;

    protected $_nome_entidade = "proposta_de_acao_grupo";
    protected $_nome_modulo = "propostadeacaogrupo";
    protected $_titulo_entidade = "Proposta de Ação Grupo";
    protected $_num_registros_padrao = 3;

    public function criarProcessPageFields($id_grupo = "", $permissao = "")
    {

        // Inicio ProcessPageField
        $fieldList = new ProcessPageFields();

        // Inicio Campos da Entidade

        $field = ProcessPageFields::FactoryMinimal("texto_proposta_de_acao_grupo", "Proposta de Ação do Grupo", 64, true, true);
        $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("id_grupo", "Grupo", 30, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = $id_grupo;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_proposta_de_acao_grupo", "Data/Hora Cadastro", 15, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = date("Y-m-d H:i:s");
        $fieldList->addProcessPageField($field);

        // ID da Entidade (Todos Possuem)
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
        $field->editable = false;
        $field->key = true;
        $fieldList->addProcessPageField($field);

        // Fim dos Campos do ProcessPageFields

        $processpage = new PanteonEscolarMyProcess($this->_context,
                $fieldList,
                $this->_titulo_entidade,
                "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
                NULL,
                $this->_nome_entidade,
                PanteonEscolarBaseDBAccess::DATABASE());

        if($permissao)
        {
            $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
        }

        else
        {
            $processpage->setPermissions(false, false, false, false);
        }

        $filtro = "";

        if($id_grupo != "")
        {
            $filtro .= " id_grupo  = " . $id_grupo." ";
        }

        if($filtro != "")
        {
            $processpage->setFilter($filtro);
        }

        return $processpage;

    }

    public function generateObject($current)
    {
        $span1 = new XmlnukeSpanCollection();
        $span1->addXmlnukeObject($this->criarProcessPageFields());
        $node = XmlUtil::CreateChild($current, $this->_nome_entidade, "");
        $body = XmlUtil::CreateChild($node, "body", "");
        parent::generatePage($body);

    }

    public function PropostaDeAcaoGrupoDBXML($context, $nome_modulo = "propostadeacaogrupo", $titulo = "Proposta de Ação do Grupo")
    {
        if(!($context instanceof Context))
        {
            throw new Exception("Falta de Context");
        }

        $this->_context = $context;
        $this->_nome_modulo = $nome_modulo;
        $this->_titulo_entidade = $titulo;

    }

}

?>
