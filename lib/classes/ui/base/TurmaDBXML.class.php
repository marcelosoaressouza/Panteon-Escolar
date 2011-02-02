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

class TurmaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "turma";
  protected $_nome_modulo = "turma";
  protected $_titulo_entidade = "Turma";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_instituicao = "", $permissao = "")
  {

    $db = new InstituicaoDB($this->_context);
    $it = $db->obterTodos();
    $arrayInstituicao = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_instituicao", "nome_instituicao");

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    if($this->_context->ContextValue("acao") == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_turma", "Turma", 30, true, true);
      $field->editable = false;
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTurmaFormatter($this->_context, "view");
      $fieldList->addProcessPageField($field);

    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("nome_turma", "Nome da Turma", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      $fieldList->addProcessPageField($field);
    }

    if($id_instituicao != "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_instituicao", "Instituição", 32, false, false);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      $field->defaultValue = $id_instituicao;
      $fieldList->addProcessPageField($field);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("id_instituicao", "Instituição", 32, true, true);
      $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
      $field->arraySelectList = $arrayInstituicao;
      $fieldList->addProcessPageField($field);

    }

    if($nivel_acesso == "GESTOR" || $nivel_acesso == "ADMINISTRADOR")
    {
      if($this->_context->ContextValue("acao") == "")
      {
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
        $field->editable = false;
        $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "turma", "configturma");
        $fieldList->addProcessPageField($field);

      }
    }

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim Campos da Entidade

    // Fim dos Campos do ProcessPageFields
    //
    // Inicio Custom Button for Set
    $button = new CustomButtons();
    $button->action = "adicionarUsuarioATurma";
    $button->alternateText = "Adicionar/Remover Usuários a Turma";
    $button->message = "Adicionar/Remover Usuários a Turma";
    $button->multiple = MultipleSelectType::ONLYONE;
    $button->icon = "common/editlist/ic_coletar.gif";
    $button->enabled = true;

    $processpage = new PanteonEscolarMyProcess($this->_context,
        $fieldList,
        $this->_titulo_entidade,
        "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
        array($button),
        $this->_nome_entidade,
        PanteonEscolarBaseDBAccess::DATABASE());

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

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

    if($id_instituicao != "")
    {
      $filtro .= " id_instituicao = " . $id_instituicao ." ";

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

  public function TurmaDBXML($context, $nome_modulo = "turma", $titulo = "Turma")
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