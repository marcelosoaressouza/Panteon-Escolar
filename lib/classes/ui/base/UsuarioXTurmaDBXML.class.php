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

class UsuarioXTurmaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "usuario_x_turma";
  protected $_nome_modulo = "usuarioxturma";
  protected $_titulo_entidade = "Usuário X Turma";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_turma = "", $id_instituicao = "", $permissao = "", $id_usuario = "")
  {

    // Inicio Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new UsuarioDB($this->_context);
    $it = $db->obterTodosOsUsuariosQueNaoEstaoNaTurmaPorIDInstituicao($id_instituicao, $id_turma);

    $arrayUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_usuario", "nome_completo_usuario");

    if($id_usuario == "")
    {
      unset($arrayUsuario[""]);
      $arrayUsuarioXTurma = $arrayUsuario;

      $db = new UsuarioXTurmaDB($this->_context);
      $it = $db->obterTodosOsUsuariosPorIDTurma($id_turma);

      while($it->hasNext())
      {
        $sr = $it->moveNext();
        unset($arrayUsuarioXTurma[$sr->getField("id_usuario")]);
      }
    }

    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    if($id_usuario != "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 30, false, false);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 30, true, true);
    }

    $field->fieldXmlInput = XmlInputObjectType::DUALLIST;
    $field->arraySelectList = $arrayUsuario;

    $fieldList->addProcessPageField($field);

    if($id_usuario != "")
    {
      $db = new TurmaDB($this->_context);
      $it = $db->obterTodos();
      $arrayTurma = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_turma", "nome_turma");

      if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
      {
        $field = ProcessPageFields::FactoryMinimal("id_turma", "Turma", 30, true, true);
        $field->editable = false;
        $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
        $field->editListFormatter = new PanteonEscolarTurmaFormatter($this->_context, "view");
        $fieldList->addProcessPageField($field);
      }

      else
      {
        $field = ProcessPageFields::FactoryMinimal("id_turma", "Turma", 30, true, true);
        $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
        $field->arraySelectList = $arrayTurma;
        $fieldList->addProcessPageField($field);

      }

    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("id_turma", "Turma", 30, false, false);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      $field->defaultValue = $id_turma;
      $fieldList->addProcessPageField($field);
    }

    if($this->_context->ContextValue("acao") != "ppnew")
    {
      if($id_usuario == "")
      {
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
        $field->editListFormatter = new PanteonEscolarUsuarioXTurmaFormatter($this->_context, 'delete');
        $field->editable = false;
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

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

    if($permissao)
    {
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    }

    else
    {
      $processpage->setPermissions(false, false, false, false);
    }

    // Filtros
    if($id_turma   != "")
    {
      $processpage->setFilter("id_turma   = " . $id_turma);
    }

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

  public function UsuarioXTurmaDBXML($context, $nome_modulo = "usuarioxturma", $titulo = "Usuário X Turma")
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
