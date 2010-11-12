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

class UsuarioXNivelAcessoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "usuario_x_nivel_acesso";
  protected $_nome_modulo = "usuarioxnivelacesso";
  protected $_titulo_entidade = "Usuário X Nivel de Acesso";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_usuario_x_nivel_acesso = "", $permissao = "") {

    // Inicio Campos da Entidade
    $fieldList = new ProcessPageFields();

    $field = ProcessPageFields::FactoryMinimal("valor_usuario_x_nivel_acesso", "Nivel de Acesso", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = array("ANALISTA" => "Analista", "EDITOR" => "Editor", "COORDENADOR" => "Coordenador","GESTOR" => "Gestor");
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("nome_usuario_x_nivel_acesso", "Nome", 32, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = 'roles';
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 32, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_usuario_x_nivel_acesso;
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

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

    if($permissao)
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    else
      $processpage->setPermissions(false, false, false, false);

    // Filtros
    $filtro = "";

    if($id_usuario_x_nivel_acesso != "") {
      $filtro .= " id_usuario = " . $id_usuario_x_nivel_acesso ." ";
      $filtro .= " AND nome_usuario_x_nivel_acesso != 'editsite'";

    }

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

  public function UsuarioXNivelAcessoDBXML($context, $nome_modulo = "UsuarioXNivelAcesso", $titulo = "Usuário") {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_nome_modulo = $nome_modulo;
    $this->_titulo_entidade = $titulo;

  }

}

?>
