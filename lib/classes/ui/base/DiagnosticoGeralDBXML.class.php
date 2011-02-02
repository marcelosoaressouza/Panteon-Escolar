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

class DiagnosticoGeralDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "diagnostico_geral";
  protected $_nome_modulo = "meudiagnosticogeral";
  protected $_titulo_entidade = "Diagnóstico Geral";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_usuario_x_tema_panteon = "", $permissao = "")
  {
    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade
    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Meu Diagnóstico Geral", 1, true, true);
      $field->editListFormatter = new PanteonEscolarTextoFormatter($this->_context, "ver", "texto_diagnostico_geral", "diagnostico_geral");
      $fieldList->addProcessPageField($field);

    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("texto_diagnostico_geral", "Meu Diagnóstico Geral", 256, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $fieldList->addProcessPageField($field);
    }

    if($this->_context->ContextValue("acao") == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "diagnostico_geral", "meudiagnosticogeral");
      $fieldList->addProcessPageField($field);

    }

    // Campo Data - Automatico
    $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_diagnostico_geral", "Data/Hora Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_usuario_x_tema_panteon", "Tema Panteon", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    //$field->arraySelectList = $arrayUsuarioXTemaPanteon;
    $field->defaultValue = $id_usuario_x_tema_panteon;
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
    {
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    }

    else
    {
      $processpage->setPermissions(false, false, false, false);
    }

    // Filtros
    if($id_usuario_x_tema_panteon != "")
    {
      $processpage->setFilter("id_usuario_x_tema_panteon = " . $id_usuario_x_tema_panteon);
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

  public function DiagnosticoGeralDBXML($context, $nome_modulo = "meudiagnosticogeral", $titulo = "Diagnóstico Geral")
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
