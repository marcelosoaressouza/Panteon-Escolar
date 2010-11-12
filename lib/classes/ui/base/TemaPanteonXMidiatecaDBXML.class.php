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

class TemaPanteonXMidiatecaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "tema_panteon_x_midiateca";
  protected $_nome_modulo = "temapanteonxmidiateca";
  protected $_titulo_entidade = "Tema Panteon X Midia.";
  protected $_num_registros_padrao = 6;

  public function criarProcessPageFields($id_tema_panteon = "", $permissao = "", $anexar = true) {
    // Inicio Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new TemaPanteonDB($this->_context);
    $it = $db->obterTodos();
    $arrayTemaPanteon = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                        "id_tema_panteon",
                        "nome_tema_panteon");

    $db = new MidiatecaDB($this->_context);
    $it = $db->obterTodos();
    $arrayMidiateca = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                      "id_midiateca",
                      "nome_midiateca");


    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade


    $field = ProcessPageFields::FactoryMinimal("id_midiateca", "Arquivo Midiateca", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->editListFormatter = new PanteonEscolarUploadByIDFormatter($this->_context, "arquivo");
    $field->arraySelectList = $arrayMidiateca;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_midiateca", "Endereço Internet (URL)", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->editListFormatter = new PanteonEscolarUploadByIDFormatter($this->_context, "url");
    $field->arraySelectList = $arrayMidiateca;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_midiateca", "Descrição", 64, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->editListFormatter = new PanteonEscolarMidiatecaDescByIDFormatter($this->_context);
    $field->arraySelectList = $arrayMidiateca;
    $fieldList->addProcessPageField($field);

    if($this->_context->ContextValue("acao") == "") {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "tema_panteon_x_midiateca", "criarmidiatecatemapanteon");
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayTemaPanteon;
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "#", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);


    // Fim dos Campos do ProcessPageFields

    $button = new CustomButtons();

    if($anexar == true) {
      $button->action = "getNovaMidiatecaTemaPanteon";
      $button->alternateText = "Anexar Novas Midia ao Tema Panteon";
      $button->message = "Anexar Novas Midia ao Tema Panteon";
      $button->multiple = MultipleSelectType::NONE;
      $button->icon = "common/editlist/ic_coletar.gif";
      $button->enabled = true;
      $butoes = array($button);

    }

    $processpage = new PanteonEscolarMyProcess($this->_context, $fieldList, $this->_titulo_entidade, "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
        $butoes, $this->_nome_entidade, PanteonEscolarBaseDBAccess::DATABASE());

    if($permissao)
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    else
      $processpage->setPermissions(false, false, false, false);

    // Filtros
    if($id_tema_panteon != "") $processpage->setFilter("id_tema_panteon = " . $id_tema_panteon);

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

    return $processpage;

  }

  public function generateObject($current) {
    $span1 = new XmlnukeSpanCollection();
    $span1->addXmlnukeObject($this->criarProcessPageFields());
    $node = XmlUtil::CreateChild($current, $this->_nome_entidade, "");
    $body = XmlUtil::CreateChild($node, "body", "");
    parent::generatePage($body);

  }

  public function TemaPanteonXMidiatecaDBXML($context, $nome_modulo = "temapanteonxmidiateca", $titulo = "Tema Panteon X Midiateca") {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_nome_modulo = $nome_modulo;
    $this->_titulo_entidade = $titulo;

  }


}

?>