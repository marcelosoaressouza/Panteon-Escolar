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

class ItemAnaliseDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "item_analise";
  protected $_nome_modulo = "itemanalise";
  protected $_titulo_entidade = "Item de Análise";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_metodo_analise = "", $permissao = "")
  {
    if($id_metodo_analise != "")
    {
      $id = $id_metodo_analise;
    }

    else
    {
      $id = $this->_context->getCookie("id_metodo_analise");
    }

    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new MetodoAnaliseDB($this->_context);
    $it = $db->obterTodos();
    $arrayMetodoAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                          "id_metodo_analise",
                          "nome_metodo_analise");
    //
    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    $field = ProcessPageFields::FactoryMinimal("nome_item_analise", "Nome", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("descricao_item_analise", "Descrição", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
    $fieldList->addProcessPageField($field);

    if($this->_context->ContextValue("acao") == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "item_analise", "criaritemdeanalise");
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("id_metodo_analise", "Método Análise", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id;
    $field->arraySelectList = $arrayMetodoAnalise;
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
        "module:panteonescolar.".$this->_nome_modulo."&amp;metodoanalise=".$id_metodo_analise."&amp;chamada=1",
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

    // Filtros
    if($id_metodo_analise != "")
    {
      $processpage->setFilter("id_metodo_analise = " . $id_metodo_analise);
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

  public function ItemAnaliseDBXML($context, $nome_modulo = "itemanalise", $titulo = "Item de Análise")
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
