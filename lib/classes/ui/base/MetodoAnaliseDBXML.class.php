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

class MetodoAnaliseDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;

    protected $_nome_entidade = "metodo_analise";
    protected $_nome_modulo = "metodoanalise";
    protected $_titulo_entidade = "Método de Análise";
    protected $_num_registros_padrao = 5;

    public function criarProcessPageFields($id_usuario = "", $permissao = "")
    {

        // Inicio ProcessPageField
        $fieldList = new ProcessPageFields();
        $fileUpload = new ProcessPageStateBaseSaveFormatterFileUpload($this->_context, "upload/metodoanalise");
        // $fileUpload->resizeImageTo(85, 85);

        // Inicio dos Campos do ProcessPageFields

        // Inicio Campos da Entidade

        if($this->_context->ContextValue("acao") != "")
        {
            $field = ProcessPageFields::FactoryMinimal("nome_metodo_analise", "Titulo", 30, true, true);
            $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
            $fieldList->addProcessPageField($field);
        }

        if($this->_context->ContextValue("acao") == "")
        {
            $field = ProcessPageFields::FactoryMinimal("id_metodo_analise", "Titulo", 16, true, true);
            $field->editListFormatter = new PanteonEscolarMetodoAnaliseFormatter($this->_context, "view");
            $field->editable = false;
            $fieldList->addProcessPageField($field);
        }

        $field = ProcessPageFields::FactoryMinimal("descricao_metodo_analise", "Descrição", 30, true, true);
        $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
        $fieldList->addProcessPageField($field);

        if($this->_context->ContextValue("acao") == "")
        {
            if($permissao[4] == true)
            {
                $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
                $field->editable = false;
                $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "metodo_analise", "meusmetodosdeanalise");
                $fieldList->addProcessPageField($field);
            }

        }

        $field = ProcessPageFields::FactoryMinimal("id_usuario", "Criador", 30, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = $id_usuario;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("caminho_foto_metodo_analise", "Imagem", 30, false, false);
        $field->fieldXmlInput = XmlInputObjectType::FILEUPLOAD;
        $field->saveDatabaseFormatter = $fileUpload;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_metodo_analise", "Data/Hora Cadastro", 15, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = date("Y-m-d H:i:s");
        $fieldList->addProcessPageField($field);

        // ID da Entidade (Todos Possuem)
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
        $field->editable = false;
        $field->key = true;
        $fieldList->addProcessPageField($field);

        // Fim dos Campos do ProcessPageFields
        //
        // Custom Button for Set
        if($permissao[4] != false)
        {
            $button = new CustomButtons();
            $button->action = "createItemAnalise";
            $button->alternateText = "Ver/Criar Itens de Análise";
            $button->message = "Ver/Criar Itens de Análise";
            $button->multiple = MultipleSelectType::ONLYONE;
            $button->icon = "common/editlist/ic_criaritemanalise.gif";
            $button->enabled = true;
            $butoes = array($button);

        }

        $processpage = new PanteonEscolarMyProcess($this->_context, $fieldList, $this->_titulo_entidade,
                "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
                $butoes, $this->_nome_entidade, PanteonEscolarBaseDBAccess::DATABASE());


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
            $processpage->setFilter("id_usuario = " . $id_usuario);
        }

        if($this->_context->ContextValue("caminho_foto_metodo_analise") != "")
        {
            $fileProcessor = new UploadFilenameProcessor('*.*', $this->_context);
            $fileProcessor->setFilenameLocation(ForceFilenameLocation::DefinePath, "upload/metodoanalise");
            $resultUpload = $this->_context->processUpload($fileProcessor, false, 'caminho_foto_metodo_analise');

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

    public function MetodoAnaliseDBXML($context, $nome_modulo = "metodoanalise", $titulo = "Metódo de Análise")
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
