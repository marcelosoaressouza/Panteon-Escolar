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

class UsuarioXPontoDeVistaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "usuario_x_ponto_de_vista";
  protected $_nome_modulo = "usuarioxpontodevista";
  protected $_titulo_entidade = "Usuário X Ponto de Vista";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_usuario = "", $id_tema_panteon = "", $permissao = "", $coletados = "") {

    // Inicio Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new PontodeVistaDB($this->_context);
    $it = $db->obterTodos();

    while($it->hasNext()) {
      $sr = $it->moveNext();

      $texto = substr($sr->getField("texto_ponto_de_vista"), 0 , 196);
      $texto = strip_tags($texto);

      $arrayPontoDeVista[$sr->getField("id_ponto_de_vista")] = $texto."<div id='aviso_texto_longo'>(Continua)</div>";

    }

    //
    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    $field = ProcessPageFields::FactoryMinimal("id_ponto_de_vista", "Ponto de Vista", 1, true, true);
    $field->editable = false;
    $field->editListFormatter = new PanteonEscolarPontoDeVistaFormatter($this->_context, "view");
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_ponto_de_vista", "Sujeito", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $field->editListFormatter = new PanteonEscolarSujeitoDescByIDFormatter($this->_context);
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_ponto_de_vista", "Item de Análise", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $field->editListFormatter = new PanteonEscolarItemAnaliseByIDFormatter($this->_context);
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_ponto_de_vista", "Situação-Problema", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $field->editListFormatter = new PanteonEscolarSituacaoProblemaByIDFormatter($this->_context);
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move")) {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Comentário", 1, true, true);
      $field->editListFormatter = new PanteonEscolarTextoFormatter($this->_context, "view","texto_usuario_x_ponto_de_vista", $this->_nome_entidade);
      $fieldList->addProcessPageField($field);
    } else {
      $field = ProcessPageFields::FactoryMinimal("texto_usuario_x_ponto_de_vista", "Comentário", 32, true, true);
      $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      $fieldList->addProcessPageField($field);
    }


    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
    $field->editable = false;
    $field->editListFormatter = new PanteonEscolarPontoDeVistaFormatter($this->_context, "delete");
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_usuario;
    $fieldList->addProcessPageField($field);

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

    if($id_usuario      != "") $filtro .= " id_usuario = " . $id_usuario." ";
    if($id_tema_panteon != "") $filtro .= " AND id_tema_panteon = " . $id_tema_panteon ." ";

    if($coletados != "") $filtro .= " AND coletado_usuario_x_ponto_de_vista =" . $coletados." ";


    if($filtro != "") $processpage->setFilter($filtro);

    return $processpage;

  }
  public function criarProcessPageFieldsColetar($id_usuario = "", $id_ponto_de_vista  = "", $id_tema_panteon = "", $coletado = "", $permissao = "") {

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade
    $field = ProcessPageFields::FactoryMinimal("texto_usuario_x_ponto_de_vista", "Comentário", 60, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_ponto_de_vista", "Ponto de Vista", 8, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_ponto_de_vista;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 8, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_usuario;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 8, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_tema_panteon;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("coletado_usuario_x_ponto_de_vista", "Coletado", 8, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $coletado;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_hora_usuario_x_ponto_de_vista", "Data/Hora Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "#", 8, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields

    $processpage = new PanteonEscolarMyProcess($this->_context, $fieldList, $this->_titulo_entidade,
        "module:panteonescolar.vercoletar&amp;chamada=1&amp;xsl=page&amp;coletar=".$coletado,
        NULL, $this->_nome_entidade, PanteonEscolarBaseDBAccess::DATABASE());


    if($permissao)
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    else
      $processpage->setPermissions(false, false, false, false);

    // Filtros
    $filtro = "";

    if($id_usuario      != "") $filtro .= " id_usuario = " . $id_usuario." ";
    if($id_tema_panteon != "") $filtro .= " AND id_tema_panteon = " . $id_tema_panteon ." ";

    if($coletados != "") $filtro .= " AND coletado_usuario_x_ponto_de_vista =" . $coletados." ";


    if($filtro != "") $processpage->setFilter($filtro);

    return $processpage;

  }

  public function generateObject($current) {
    $span1 = new XmlnukeSpanCollection();
    $span1->addXmlnukeObject($this->criarProcessPageFields());
    $node = XmlUtil::CreateChild($current, $this->_nome_entidade, "");
    $body = XmlUtil::CreateChild($node, "body", "");
    parent::generatePage($body);

  }

  public function UsuarioXPontoDeVistaDBXML($context, $nome_modulo = "usuarioxpontodevista", $titulo = "Usuário X Ponto de Vista") {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_nome_modulo = $nome_modulo;
    $this->_titulo_entidade = $titulo;

  }

}

?>