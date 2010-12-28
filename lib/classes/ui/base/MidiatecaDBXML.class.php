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

class MidiatecaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;

    protected $_nome_entidade = "midiateca";
    protected $_nome_modulo = "midiateca";
    protected $_titulo_entidade = "Midiateca";
    protected $_num_registros_padrao = 4;

    public function criarProcessPageFields($permissao = "", $id_usuario = "")
    {

        // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
        $db = new TipoMidiaDB($this->_context);
        $it = $db->obterTodos();
        $arrayTipoMidia = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_tipo_midia", "nome_tipo_midia");

        // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

        // Inicio ProcessPageField
        $fieldList = new ProcessPageFields();
        $fileUpload = new ProcessPageStateBaseSaveFormatterFileUpload($this->_context, "upload/midiateca");
        // $fileUpload->resizeImageTo(85, 85);

        // Inicio dos Campos do ProcessPageFields

        // Inicio Campos da Entidade
        $field = ProcessPageFields::FactoryMinimal("id_tipo_midia", "Tipo Mídia", 32, true, true);

        if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "ppmsgs"))
        {
            $field->editListFormatter = new PanteonEscolarTipoMidiaFormatter($this->_context);

        }

        else
        {
            $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
            $field->arraySelectList = $arrayTipoMidia;

        }

        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("nome_midiateca", "Título", 55, true, true);
        $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
        // $field->editListFormatter = new PanteonEscolarTituloURLFormatter();
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("descricao_midiateca", "Descrição", 256, true, true);
        $field->editListFormatter = new PanteonEscolarTextoFormatter();
        $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
        $field->required = true;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("tag_midiateca", "Palavra(s) Chave", 45, false, false);
        $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
        $field->saveDatabaseFormatter = new  PanteonEscolarTagFormatter($this->_context);
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("caminho_arquivo_midiateca", "Arquivo", 30, true, false);
        $field->fieldXmlInput = XmlInputObjectType::FILEUPLOAD;
        $field->saveDatabaseFormatter = $fileUpload;
        $field->editListFormatter = new PanteonEscolarUploadFormatter();
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("url_midiateca", "Endereço Internet http://", 55, true, false);
        $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
        $field->editListFormatter = new PanteonEscolarTituloURLFormatter();
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_midiateca", "Data/Hora Cadastro", 15, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = date("Y-m-d H:i:s");
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("id_usuario", "Criador", 16, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = $id_usuario;
        $fieldList->addProcessPageField($field);

        // ID da Entidade (Todos Possuem)
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "-", 4, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->editable = false;
        $field->key = true;

        $fieldList->addProcessPageField($field);

        // Fim dos Campos do ProcessPageFields

        $button = new CustomButtons();
        $button->action = "getMidiatecaTemaPanteon";
        $button->alternateText = "Anexar Midiateca ao Tema Panteon";
        $button->message = "Anexar Midiateca ao Tema Panteon";
        $button->multiple = MultipleSelectType::ONLYONE;
        $button->icon = "common/editlist/ic_coletarmidiateca.gif";
        $button->enabled = true;

        $processpage = new PanteonEscolarMyProcess($this->_context,
                $fieldList,
                $this->_titulo_entidade,
                "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
                array($button),
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


        if($this->_context->ContextValue("caminho_arquivo_midiateca") != "")
        {
            $fileProcessor = new UploadFilenameProcessor('*.*', $this->_context);
            $fileProcessor->setFilenameLocation(ForceFilenameLocation::DefinePath, "upload/midiateca");
            $resultUpload = $this->_context->processUpload($fileProcessor, false, 'caminho_arquivo_midiateca');

        }

        //
        // Filtros
        //

        $filtro = "";

        $nome_tag  = $this->_context->ContextValue("nome_tag_filtro");
        $id_tipo_midia  = $this->_context->ContextValue("id_tipo_midia_filtro");

        if($id_usuario !="")
        {
            $filtro .= " id_usuario = "  . $id_usuario ." ";
        }

        if($id_tipo_midia != "")
        {
            if($id_tipo_midia == 'All')
            {
                $filtro .= " ";

            }

            else
            {
                if($id_usuario !="")
                {
                    $filtro .= " AND ";
                }

                $filtro .= " id_tipo_midia = ". $id_tipo_midia . " ";

            }

        }

        if($nome_tag != "")
        {
            $tag = new MidiatecaDB($this->_context);
            $itTag = $tag->obterTodasAsMidiatecasPorNomeTag($nome_tag);

            if($itTag->hasNext())
            {
                if($id_usuario !="")
                {
                    $filtro .= " AND ";
                }

                while($itTag->hasNext())
                {
                    $sr = $itTag->moveNext();
                    $filtro .= " id_midiateca = "  . $sr->getField("id_midiateca") ." ";

                    if($itTag->hasNext())
                    {
                        $filtro .= " OR ";
                    }

                }
            }

            else
            {
                $filtro .= " id_midiateca = 1";

            }

        }

        if($filtro != "")
        {
            $processpage->setFilter($filtro);
        }

        $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));
        $processpage->setSort("data_hora_cadastro_midiateca DESC");

        return $processpage;

    }

    public function criarProcessPageFieldsPorTemaPanteon($id_tema_panteon = "", $permissao = "")
    {

        // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
        $db = new TipoMidiaDB($this->_context);
        $it = $db->obterTodos();
        $arrayTipoMidia = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_tipo_midia", "nome_tipo_midia");

        // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

        // Inicio ProcessPageField
        $fieldList = new ProcessPageFields();
        $fileUpload = new ProcessPageStateBaseSaveFormatterFileUpload($this->_context, "upload/midiateca");
        // $fileUpload->resizeImageTo(85, 85);

        // Inicio dos Campos do ProcessPageFields

        // Inicio Campos da Entidade
        $field = ProcessPageFields::FactoryMinimal("id_tipo_midia", "Tipo Mídia", 32, true, true);

        if($this->_context->ContextValue("acao") == "")
        {
            $field->editListFormatter = new PanteonEscolarTipoMidiaFormatter($this->_context);

        }

        else
        {
            $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
            $field->arraySelectList = $arrayTipoMidia;

        }

        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("nome_midiateca", "Título", 55, true, true);
        $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("descricao_midiateca", "Descrição", 256, true, true);
        $field->editListFormatter = new PanteonEscolarTextoFormatter();
        $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
        $field->required = true;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("caminho_arquivo_midiateca", "Arquivo", 30, true, false);
        $field->fieldXmlInput = XmlInputObjectType::FILEUPLOAD;
        $field->saveDatabaseFormatter = $fileUpload;
        $field->editListFormatter = new PanteonEscolarUploadFormatter();
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("url_midiateca", "Endereço Internet http://", 55, true, false);
        $field->editListFormatter = new PanteonEscolarTituloURLFormatter();
        $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("tag_midiateca", "Palavra(s)-Chave(s)", 64, false, false);
        $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
        $field->saveDatabaseFormatter = new  PanteonEscolarTagFormatter($this->_context);
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_midiateca", "Data/Hora Cadastro", 15, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = date("Y-m-d H:i:s");
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("id_usuario", "Criador", 16, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = $id_usuario;
        $fieldList->addProcessPageField($field);

        // ID da Entidade (Todos Possuem)
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "-", 4, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
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


        if($this->_context->ContextValue("caminho_arquivo_midiateca") != "")
        {
            $fileProcessor = new UploadFilenameProcessor('*.*', $this->_context);
            $fileProcessor->setFilenameLocation(ForceFilenameLocation::DefinePath, "upload/midiateca");
            $resultUpload = $this->_context->processUpload($fileProcessor, false, 'caminho_arquivo_midiateca');

        }

        //
        // Filtros
        //

        $filtro = "";

        $nome_tag  = $this->_context->ContextValue("nome_tag_filtro");
        $id_tipo_midia  = $this->_context->ContextValue("id_tipo_midia_filtro");

        if($id_tema_panteon !="")
        {
            $db = new TemaPanteonXMidiatecaDB($this->_context);
            $itMidiateca = $db->obterTodosOsTemasPanteonPorIDMidiateca($id_tema_panteon);

            $filtro .= " ( ";

            while($itMidiateca->hasNext())
            {
                $sr = $itMidiateca->moveNext();
                $filtro .= " id_midiateca = "  . $sr->getField("id_midiateca") ." ";

                if($itMidiateca->hasNext())
                {
                    $filtro .= " OR ";
                }

            }

            $filtro .= " ) ";

        }

        if($id_tipo_midia != "")
        {

            if($id_tipo_midia == 'All')
            {
                $filtro .= " ";

            }

            else
            {
                if($id_tema_panteon !="")
                {
                    $filtro .= " AND ";
                }

                $filtro .= " id_tipo_midia = '". $id_tipo_midia . "' ";

            }

        }

        if($nome_tag != "")
        {
            $tag = new MidiatecaDB($this->_context);
            $itTag = $tag->obterTodasAsMidiatecasPorNomeTag($nome_tag);

            if($itTag->hasNext())
            {
                if($id_tema_panteon !="")
                {
                    $filtro .= " AND ";
                }

                while($itTag->hasNext())
                {
                    $sr = $itTag->moveNext();
                    $filtro .= " id_midiateca = "  . $sr->getField("id_midiateca") ." ";

                    if($itTag->hasNext())
                    {
                        $filtro .= " OR ";
                    }

                }
            }

            else
            {
                $filtro .= " id_midiateca = 1";

            }

        }

        if($filtro != "")
        {
            $processpage->setFilter($filtro);
        }

        $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));
        $processpage->setSort("data_hora_cadastro_midiateca DESC");

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

    public function MidiatecaDBXML($context, $nome_modulo = "midiateca", $titulo = "Midiateca")
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
