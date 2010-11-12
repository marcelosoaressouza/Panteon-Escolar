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

class UsuarioXGrupoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "usuario_x_grupo";
  protected $_nome_modulo = "usuarioxgrupo";
  protected $_titulo_entidade = "Usuário X Grupo";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_usuario = "", $permissao = "", $id_grupo = "") {

    // Inicio Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    if($id_grupo != "") {
      $db = new GrupoDB($this->_context);
      $id_tema_panteon = $db->obterPorId($id_grupo)->getIDTemaPanteon();

      $db = new UsuarioXTemaPanteonDB($this->_context);
      $it = $db->obterTodosOsUsuariosPorIDTemaPanteon($id_tema_panteon);
      $arrayUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_usuario", "nome_completo_usuario");
    }
    //
    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    if($id_grupo != "")
      $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 30, true, true);
    else
      $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayUsuario;
    $fieldList->addProcessPageField($field);

    if($id_grupo != "") {
      $field = ProcessPageFields::FactoryMinimal("id_grupo", "Grupo", 30, false, false);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      $field->defaultValue = $id_grupo;
      $fieldList->addProcessPageField($field);

    } else {
      $db = new GrupoDB($this->_context);
      $it = $db->obterTodos();
      $arrayGrupo = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_grupo", "nome_grupo");

      if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move")) {
        $field = ProcessPageFields::FactoryMinimal("id_grupo", "Grupo", 30, true, true);
        $field->editable = false;
        $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
        $field->editListFormatter = new PanteonEscolarGrupoFormatter($this->_context, "view");
        $fieldList->addProcessPageField($field);
      } else {
        $field = ProcessPageFields::FactoryMinimal("id_grupo", "Grupo", 30, true, true);
        $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
        $field->arraySelectList = $arrayGrupo;
        $fieldList->addProcessPageField($field);

      }

      $field = ProcessPageFields::FactoryMinimal("id_grupo", "Tema Panteon", 30, true, true);
      $field->editable = false;
      $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      $field->editListFormatter = new PanteonEscolarGrupoXTemaPanteonFormatter($this->_context);
      $fieldList->addProcessPageField($field);

    }

    if($this->_context->ContextValue("acao") == "") {
      if($id_usuario == "") {
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
        $field->editable = false;
        $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "usuario_x_grupo", "criarusuarioxgrupo");
        $fieldList->addProcessPageField($field);
      }

    }

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
    if($id_usuario != "") $processpage->setFilter(" id_usuario = " . $id_usuario);
    if($id_grupo   != "") $processpage->setFilter(" id_grupo   = " . $id_grupo);

    return $processpage;

  }

  public function generateObject($current) {
    $span1 = new XmlnukeSpanCollection();
    $span1->addXmlnukeObject($this->criarProcessPageFields());
    $node = XmlUtil::CreateChild($current, $this->_nome_entidade, "");
    $body = XmlUtil::CreateChild($node, "body", "");
    parent::generatePage($body);

  }

  public function UsuarioXGrupoDBXML($context, $nome_modulo = "usuarioxgrupo", $titulo = "Usuário X Grupo") {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_nome_modulo = $nome_modulo;
    $this->_titulo_entidade = $titulo;

  }

}

?>
