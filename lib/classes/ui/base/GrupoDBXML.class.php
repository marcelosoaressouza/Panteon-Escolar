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

class GrupoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "grupo";
  protected $_nome_modulo = "grupo";
  protected $_titulo_entidade = "Grupo";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_tema_panteon = "", $permissao = "")
  {

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_grupo", "Grupo", 30, true, true);
      $field->editable = false;
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarGrupoFormatter($this->_context, "view");
      $fieldList->addProcessPageField($field);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("nome_grupo", "Grupo", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      $fieldList->addProcessPageField($field);

      //$db = new UsuarioDB($this->_context);
      //$it = $db->obterTodos();
      //$arrayUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_usuario", "nome_completo_usuario");

      $field = ProcessPageFields::FactoryMinimal("id_usuario", "Responsavel", 16, false, false);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      //$field->arraySelectList = $arrayUsuario;
      $field->defaultValue = $this->_context->authenticatedUserId();
      $fieldList->addProcessPageField($field);

    }

    if($this->_context->ContextValue("acao") == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "grupo", "criargrupo");
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_tema_panteon;
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields
    //
    // Inicio Custom Button for Set
    $button = new CustomButtons();
    $button->action = "adicionarUsuarioAoGrupo";
    $button->alternateText = "Adicionar/Remover Usuários ao Grupo";
    $button->message = "Adicionar/Remover Usuários ao Grupo";
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

    if($id_tema_panteon != "")
    {
      $filtro .= " id_tema_panteon = " . $id_tema_panteon ." ";

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

  public function GrupoDBXML($context, $nome_modulo = "grupo", $titulo = "Grupo")
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
