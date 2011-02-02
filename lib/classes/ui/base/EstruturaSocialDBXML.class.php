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

class EstruturaSocialDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "estrutura_social";
  protected $_nome_modulo = "estruturasocial";
  protected $_titulo_entidade = "Estrutura Social";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_usuario = "", $permissao = "")
  {
    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    if($this->_context->ContextValue("acao") !== "")
    {
      $field = ProcessPageFields::FactoryMinimal("nome_estrutura_social", "Estrutura Social", 45, false, false);
      $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      $fieldList->addProcessPageField($field);
    }

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Criador", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_usuario;
    $fieldList->addProcessPageField($field);

    if($this->_context->ContextValue("acao") == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_estrutura_social", "Estrutura Social", 16, true, true);
      $field->editListFormatter = new PanteonEscolarEstruturaSocialFormatter($this->_context, "view");
      $field->editable = false;
      $fieldList->addProcessPageField($field);

      if($permissao[4] == true)
      {
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
        $field->editable = false;
        $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "estrutura_social", "minhasestruturassociais");
        $fieldList->addProcessPageField($field);
      }

    }

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields
    //
    // Custom Button for Set
    $button = new CustomButtons();

    if($permissao[4] != false)
    {
      $button->action = "createGrupoSocial";
      $button->alternateText = "Ver/Criar Grupo Social";
      $button->message = "Ver/Criar Grupo Social";
      $button->multiple = MultipleSelectType::ONLYONE;
      $button->icon = "common/editlist/ic_criargruposocial.gif";
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

  public function EstruturaSocialDBXML($context, $nome_modulo = "estruturasocial", $titulo = "Estrutura Social")
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
