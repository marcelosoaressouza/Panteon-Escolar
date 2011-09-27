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

class PropostaDeAcaoGeralDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "proposta_de_acao_geral";
  protected $_nome_modulo = "propostadeacaogeral";
  protected $_titulo_entidade = "Proposta de Ação Geral";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_usuario = "", $id_tema_panteon = "", $permissao = "")
  {

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio dos Campos do ProcessPageFields
    $field = ProcessPageFields::FactoryMinimal("nome_proposta_de_acao_geral", "Titulo", 50, false, true);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = " ";
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Minha Proposta", 1, true, true);
      $field->editListFormatter = new PanteonEscolarTextoFormatter($this->_context, "ver", "texto_proposta_de_acao_geral", "proposta_de_acao_geral");
      $fieldList->addProcessPageField($field);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("texto_proposta_de_acao_geral", "Minha Proposta", 128, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $fieldList->addProcessPageField($field);
    }

    if($this->_context->ContextValue("acao") == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "proposta_de_acao_geral", "minhapropostadeacaogeral");
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 16, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_tema_panteon;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 16, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_usuario;
    $fieldList->addProcessPageField($field);

    // Campo Data - Automatico
    $field = ProcessPageFields::FactoryMinimal("data_cadastro_proposta_de_acao_geral", "Data Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d");
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields
//xmlnuke.php?module=panteonescolar.minhapropostadeacaogeral

    $processpage = new PanteonEscolarMyProcess($this->_context, $fieldList, $this->_titulo_entidade,
        "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1", NULL, $this->_nome_entidade,
        PanteonEscolarBaseDBAccess::DATABASE());
    //Debug::PrintValue($this->_nome_modulo);

    if($permissao)
    {
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    }

    else
    {
      $processpage->setPermissions(false, false, false, false);
    }

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

    // Filtros
    $filtro = "";

    if($id_usuario != "")
    {
      $filtro .= "id_usuario = " . $id_usuario;
    }

    if($id_tema_panteon != "")
    {
      $filtro .= " AND id_tema_panteon = " . $id_tema_panteon;
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

  public function PropostaDeAcaoGeralDBXML($context, $nome_modulo = "propostadeacaogeral", $titulo = "Proposta de Ação Geral")
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
