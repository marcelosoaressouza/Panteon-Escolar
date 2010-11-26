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

class MuralDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "mural";
  protected $_nome_modulo = "Mural";
  protected $_titulo_entidade = "mural";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_usuario = "", $id_tema_panteon = "", $permissao = "") {

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    $field = ProcessPageFields::FactoryMinimal("nome_mural", "Título Notícia do Mural", 49, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move")) {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Notícia", 1, true, true);
      $field->editListFormatter = new PanteonEscolarTextoFormatter($this->_context, "ver", "texto_mural", "mural");
      $fieldList->addProcessPageField($field);
    } else {
      $field = ProcessPageFields::FactoryMinimal("texto_mural", "Texto", 64, true, true);
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $fieldList->addProcessPageField($field);
    }

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move")) {
      if($permissao[4] == true) {
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
        $field->editable = false;
        $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "mural", "criarmural");
        $fieldList->addProcessPageField($field);
      }

    }

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Sobre qual Tema Panteon", 30, false, false);
    // $field->editListFormatter = new PanteonEscolarMinhaMuralFormatter($this->_context);
    // $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    // $field->arraySelectList = $arrayTemaPanteon;
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_tema_panteon;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_usuario;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_mural", "Data Cadastro", 15, false, false);
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
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    else
      $processpage->setPermissions(false, false, false, false);

    // Filtros
    $filtro = "";

//    if($id_usuario != "") {
//      $filtro .= " id_usuario = " . $id_usuario;

    if($id_tema_panteon != "") {
      $filtro .= " id_tema_panteon = " . $id_tema_panteon;
    }
//      if($id_tema_panteon != "") { $filtro .= " AND id_tema_panteon = " . $id_tema_panteon; }

//      $processpage->setFilter($filtro);
//    }

    return $processpage;

  }

  public function criarProcessPageFieldsPorTemaPanteon($id_tema_panteon = "", $permissao = "") {
    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
    $db = new MuralDB($this->_context);
    $it = $db->obterTodos();
    $arrayMural = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_mural", "nome_mural");

    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    $field = ProcessPageFields::FactoryMinimal("nome_mural", "Título Notícia do Mural", 49, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move")) {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Notícia", 1, true, true);
      $field->editListFormatter = new PanteonEscolarTextoFormatter($this->_context, "ver", "texto_mural", "mural");
      $fieldList->addProcessPageField($field);
    } else {
      $field = ProcessPageFields::FactoryMinimal("texto_mural", "Texto", 64, true, true);
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $fieldList->addProcessPageField($field);
    }

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move")) {
      if($permissao[4] == true) {
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
        $field->editable = false;
        $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "mural", "criarmural");
        $fieldList->addProcessPageField($field);
      }

    }

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Sobre qual Tema Panteon", 30, false, false);
    // $field->editListFormatter = new PanteonEscolarMinhaMuralFormatter($this->_context);
    // $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    // $field->arraySelectList = $arrayTemaPanteon;
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_tema_panteon;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_usuario;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_mural", "Data Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->arraySelectList = $arrayMural;
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
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    else
      $processpage->setPermissions(false, false, false, false);

    // Filtros
    $filtro = "";

    return $processpage;
  }

  public function generateObject($current) {
    $span1 = new XmlnukeSpanCollection();
    $span1->addXmlnukeObject($this->criarProcessPageFields());
    $node = XmlUtil::CreateChild($current, $this->_nome_entidade, "");
    $body = XmlUtil::CreateChild($node, "body", "");
    parent::generatePage($body);

  }

  public function MuralDBXML($context, $nome_modulo = "Mural", $titulo = "Mural") {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_nome_modulo = $nome_modulo;
    $this->_titulo_entidade = $titulo;

  }


}

?>
