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

class UsuarioXTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;

    protected $_nome_entidade = "usuario_x_tema_panteon";
    protected $_nome_modulo = "usuarioxtemapanteon";
    protected $_titulo_entidade = "Usuário X Tema Panteon";
    protected $_num_registros_padrao = 5;

    public function criarProcessPageFields($id_usuario = "", $permissao = "", $qtd_linhas = 5, $id_tema_panteon = "")
    {

        // Inicio Obtencao de dados de Tabelas Auxiliares-Relacionadas
        //
        $db = new UsuarioDB($this->_context);
        $it = $db->obterTodos();
        $arrayUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                        "id_usuario",
                        "nome_completo_usuario");

        $db = new TemaPanteonDB($this->_context);
        $it = $db->obterTodos();
        $arrayTemaPanteon = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                            "id_tema_panteon",
                            "nome_tema_panteon");

        $db = new PermissaoDB($this->_context);
        $it = $db->obterTodos();
        $arrayPermissao = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                          "id_permissao",
                          "nome_permissao");

        //
        // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

        // Inicio ProcessPageField
        $fieldList = new ProcessPageFields();

        // Inicio Campos da Entidade

        if($id_tema_panteon != "")
        {
            $field = ProcessPageFields::FactoryMinimal("id_usuario", "Nome Completo", 30, true, true);
        }

        else
        {
            $field = ProcessPageFields::FactoryMinimal("id_usuario", "Nome Completo", 30, false, false);
        }

        $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
        $field->arraySelectList = $arrayUsuario;
        $fieldList->addProcessPageField($field);

        if($id_tema_panteon != "")
        {
            $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 30, false, false);
        }

        else
        {
            $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 30, true, true);
        }

        $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
        $field->arraySelectList = $arrayTemaPanteon;
        $fieldList->addProcessPageField($field);

        if($id_tema_panteon != "")
        {
            $field = ProcessPageFields::FactoryMinimal("id_permissao", "Minha Permissão", 30, false, false);
        }

        else
        {
            $field = ProcessPageFields::FactoryMinimal("id_permissao", "Minha Permissão", 30, true, true);
        }

        $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
        $field->arraySelectList = $arrayPermissao;
        $fieldList->addProcessPageField($field);

        if($id_tema_panteon != "")
        {
            $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Selecione", 1, false, false);
        }

        else
        {
            $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Selecione", 1, true, true);
        }

        $field->editable = false;
        $field->editListFormatter = new PanteonEscolarTemaPanteonFormatter($this->_context);
        $fieldList->addProcessPageField($field);

        if($id_tema_panteon != "")
        {
            $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Retirar?", 1, false, false);
        }

        else
        {
            $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Retirar?", 1, true, true);
        }

        $field->editable = false;
        $field->editListFormatter = new PanteonEscolarTemaPanteonFormatter($this->_context, 'apagarTemaPanteon');
        $fieldList->addProcessPageField($field);

        if(($id_tema_panteon != "") && ($this->_context->ContextValue("acao") == ""))
        {
            $field = ProcessPageFields::FactoryMinimal("id_usuario", "Instituição", 30, true, true);
            $field->editListFormatter = new PanteonEscolarPesquisadorFormatter($this->_context);
            $fieldList->addProcessPageField($field);

        }

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

        $processpage->setPageSize($qtd_linhas, $this->_context->ContextValue("curpage"));

        if($permissao)
        {
            $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
        }

        else
        {
            $processpage->setPermissions(false, false, false, false);
        }

        // Filtros
        if($id_usuario != "")
        {
            $processpage->setFilter(" id_usuario = " . $id_usuario." ");
        }

        if($id_tema_panteon != "")
        {
            $processpage->setFilter(" id_tema_panteon = " . $id_tema_panteon." ");
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

    public function UsuarioXTemaPanteonDBXML($context, $nome_modulo = "usuarioxtemapanteon", $titulo = "Usuário X T. Panteon")
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