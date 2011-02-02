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

class PropostaDeAcaoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "proposta_de_acao";
  protected $_nome_modulo = "propostadeacao";
  protected $_titulo_entidade = "Proposta de Ação";
  protected $_num_registros_padrao = 3;

  public function criarProcessPageFields($id_usuario = "", $id_tema_panteon = "", $permissao = "")
  {

    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
    $db = new UsuarioXTemaPanteonDB($this->_context);
    $id_usuarioxtemapanteon = $db->obterIDPorIDUsuarioXIDTemaPanteon($id_usuario, $id_tema_panteon);

    $db = new DiagnosticoIndividualDB($this->_context);
    $it = $db->obterTodosOsDiagnosticosIndividuaisPorIDIUsuarioXTemaPanteon($id_usuarioxtemapanteon);

    if(($it->Count() <= 0) && ($this->_context->ContextValue("acao") != "ppnew"))
    {
      return false;
    }

    $arrayDiagnosticoIndividual = array();

    while($it->hasNext())
    {
      $sr = $it->moveNext();
      $arrayDiagnosticoIndividual[$sr->getField("id_diagnostico_individual")] = strip_tags(mb_substr($sr->getField("texto_diagnostico_individual"), 0 , 50,'UTF-8'))."...";

    }

    //$arrayDiagnosticoIndividual = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_diagnostico_individual", "texto_diagnostico_individual");

    $db = new UsuarioDB($this->_context);
    $it = $db->obterTodos();
    $arrayUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_usuario", "nome_completo_usuario");

    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio dos Campos do ProcessPageFields

    // Inicio Campos da Entidade

    $field = ProcessPageFields::FactoryMinimal("id_diagnostico_individual", "Diagnostico Individual", 16, true, false);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayDiagnosticoIndividual;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("nome_proposta_de_acao", "Titulo", 45, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Minha Proposta", 1, true, true);
      $field->editListFormatter = new PanteonEscolarTextoFormatter($this->_context, "ver", "texto_proposta_de_acao", "proposta_de_acao");
      $fieldList->addProcessPageField($field);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("texto_proposta_de_acao", "Minha Proposta", 128, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $fieldList->addProcessPageField($field);
    }

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_diagnostico_individual", "Item Análise", 32, true, false);
      $field->editListFormatter = new PanteonEscolarDiagnosticoIndividualFormatter($this->_context, "item_analise");
      $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      $field->editable = false;
      $fieldList->addProcessPageField($field);

      $field = ProcessPageFields::FactoryMinimal("id_diagnostico_individual", "Situação-Problema", 32, true, false);
      $field->editListFormatter = new PanteonEscolarDiagnosticoIndividualFormatter($this->_context, "situacao_problema");
      $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      $field->editable = false;
      $fieldList->addProcessPageField($field);

    }

    if($this->_context->ContextValue("acao") == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "proposta_de_acao", "minhaspropostasdeacao");
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 16, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->arraySelectList = $arrayUsuario;
    $field->defaultValue = $id_usuario;
    $fieldList->addProcessPageField($field);

    // Campo Data - Automatico
    $field = ProcessPageFields::FactoryMinimal("data_cadastro_proposta_de_acao", "Data Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d");
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

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

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

  public function PropostaDeAcaoDBXML($context, $nome_modulo = "propostadeacao", $titulo = "Proposta de Ação")
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
