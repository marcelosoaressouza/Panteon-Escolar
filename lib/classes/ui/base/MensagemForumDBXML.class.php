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

class MensagemForumDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;

    protected $_nome_entidade = "mensagem_forum";
    protected $_nome_modulo = "mensagemforum";
    protected $_titulo_entidade = "Mensagem Forum";
    protected $_num_registros_padrao = 5;

    public function criarProcessPageFields($id_forum = "", $id_usuario = "", $permissao = "", $id_mensagem_original = NULL, $respostas = false, $id_usuario_por = "")
    {

        // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas

        $db = new MensagemForumDB($this->_context);
        $it = $db->obterTodos();

        if($id_mensagem_original != NULL)
        {
            $dbUsuario = new UsuarioDB($this->_context);

            $nomeMensagemOriginalForum = $db->obterPorId($id_mensagem_original)->getNomeMensagemForum();
            $autorMensagemOriginalForum = $dbUsuario->obterPorId($db->obterPorId($id_mensagem_original)->getIDUsuario())->getNomeCompletoUsuario();
        }

        $arrayMensagemResposta = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_mensagem_forum", "nome_mensagem_forum");

        // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

        // Inicio ProcessPageField
        $fieldList = new ProcessPageFields();

        // Inicio Campos da Entidade

        $field = ProcessPageFields::FactoryMinimal("nome_mensagem_forum", "Título", 30, true, true);
        $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("texto_mensagem_forum", "Mensagem", 30, true, true);
        $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("id_usuario", "Enviado por", 30, true, true);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = $id_usuario_por;
        $field->editListFormatter = new PanteonEscolarNomeUsuarioPorID($this->_context);
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("id_mensagem_resposta", "Mensagem Resposta", 64, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = $id_mensagem_original;
        $fieldList->addProcessPageField($field);

        if($id_mensagem_original != NULL)
        {
            $field = ProcessPageFields::FactoryMinimal("nome_mensagem_original", "Título Mensagem Original", 64, false, false);
            $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
            $field->editable = false;
            $field->defaultValue = $nomeMensagemOriginalForum;
            $fieldList->addProcessPageField($field);

            $field = ProcessPageFields::FactoryMinimal("nome_autor_mensagem_original", "Autor Mensagem Original", 64, false, false);
            $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
            $field->editable = false;
            $field->defaultValue = $autorMensagemOriginalForum;
            $fieldList->addProcessPageField($field);

        }

        $field = ProcessPageFields::FactoryMinimal("id_forum", "Forum", 30, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = $id_forum;
        $fieldList->addProcessPageField($field);

        $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_mensagem_forum", "Data/Hora Cadastro", 15, false, false);
        $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
        $field->defaultValue = date("Y-m-d H:i:s");
        $fieldList->addProcessPageField($field);

        // ID da Entidade (Todos Possuem)
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
        $field->editable = false;
        $field->key = true;
        $fieldList->addProcessPageField($field);

        // Fim dos Campos do ProcessPageFields
        $button = new CustomButtons();

        $button->action = "responderMensagem";
        $button->alternateText = "Responder Mensagem";
        $button->message = "Responder Mensagem";
        $button->multiple = MultipleSelectType::ONLYONE;
        $button->icon = "common/editlist/ic_respondermensagem.gif";
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

        // Filtros
        $filtro = "";

        if($id_forum   != "")
        {
            $filtro .= " id_forum = "  . $id_forum ." ";
        }

        if(($id_usuario != "") && ($respostas == false))
        {
            $filtro .= " AND id_usuario = " . $id_usuario ." ";
        }

        if($respostas == true)
        {
            $itMensagemForum = $db->obterTodasAsMensagensForumPorIDUsuario($id_usuario);

            if($itMensagemForum->hasNext())
            {
                $filtro .= " AND ";

                while($itMensagemForum->hasNext())
                {
                    $sr = $itMensagemForum->moveNext();
                    $filtro .= " id_mensagem_resposta = ".$sr->getField("id_mensagem_forum");

                    if($itMensagemForum->hasNext())
                    {
                        $filtro .= " OR ";
                    }

                }

                $filtro .= " ";

            }

        }

        if($filtro != "")
        {
            $processpage->setFilter($filtro);
        }

        $processpage->setSort(" data_hora_cadastro_mensagem_forum DESC ");



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

    public function MensagemForumDBXML($context, $nome_modulo = "forum", $titulo = "Forum")
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
