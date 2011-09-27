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

class UsuarioDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "usuario";
  protected $_nome_modulo = "usuario";
  protected $_titulo_entidade = "Usuário";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_instituicao = "", $permissao = "", $id_turma = "")
  {

    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new InstituicaoDB($this->_context);
    $it = $db->obterTodos();
    $arrayInstituicao = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                        "id_instituicao",
                        "nome_instituicao");

    $db = new PerfilDB($this->_context);
    $it = $db->obterTodos();

    while($it->hasNext())
    {
      $sr = $it->moveNext();

      $txt = strip_tags(substr($sr->getField("texto_perfil"), 0 , 190));
      $url_link = $this->_context->bindModuleUrl("panteonescolar.verperfil");
      $link = $url_link.'&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;verperfil='.$sr->getField('id_perfil');
      $url = '<b><a class="lista_direita_detalhe" href="'.$link.'">Ver Perfil</a></b>';

      $arrayPerfil[$sr->getField('id_usuario')] =  $txt."<div id='aviso_texto_longo'>".$url."</div>";

    }

    //
    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio dos Campos do ProcessPageFields

    // Inicio Campos da Entidade
    $field = ProcessPageFields::FactoryMinimal("login_usuario", "Login", 16, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("nome_completo_usuario", "Nome Completo", 50, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("email_usuario", "E-Mail", 40, true, true);
    $field->dataType = INPUTTYPE::EMAIL;
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("senha_usuario", "Senha", 10, false, true);
    $field->fieldXmlInput = XmlInputObjectType::PASSWORD;
    $field->saveDatabaseFormatter = new PanteonEscolarPasswordFormatter();
    $fieldList->addProcessPageField($field);

    if($id_instituicao != "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_instituicao", "Instituição", 32, false, false);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      $field->defaultValue = $id_instituicao;
      $fieldList->addProcessPageField($field);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("id_instituicao", "Instituição", 32, true, true);
      $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
      $field->arraySelectList = $arrayInstituicao;
      $fieldList->addProcessPageField($field);

    }

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      // $field = ProcessPageFields::FactoryMinimal("id_usuario", "Nivel de Acesso", 30, true, false);
      // $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      // $field->editListFormatter = new PanteonEscolarUsuarioFormatter($this->_context, 'niveldeacesso');
      // $field->editable = false;
      // $fieldList->addProcessPageField($field);

      $field = ProcessPageFields::FactoryMinimal("id_usuario", "Perfil", 30, true, false);
      $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
      $field->arraySelectList = $arrayPerfil;
      $field->editable = false;
      $fieldList->addProcessPageField($field);

      $id_usuario = $this->_context->authenticatedUserId();
      $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

      if($nivel_acesso == "GESTOR" || $nivel_acesso == "ADMINISTRADOR")
      {
        $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
        $field->editListFormatter = new PanteonEscolarUsuarioFormatter($this->_context, 'delete');
        $field->editable = false;
        $fieldList->addProcessPageField($field);
      }

    }

    $field = ProcessPageFields::FactoryMinimal("data_cadastro_usuario", "Data Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d");
    $fieldList->addProcessPageField($field);


    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields
    //
    //
    // Inicio Custom Button for Set
    $button = new CustomButtons();
    $button->action = "configNivelAcesso";
    $button->alternateText = "Definir Nivel de Acesso";
    $button->message = "Definir Nivel de Acesso";
    $button->multiple = MultipleSelectType::ONLYONE;
    $button->icon = "common/editlist/ic_coletar.gif";
    $button->enabled = true;

    if($nivel_acesso == "EDITOR")
    {
      $processpage = new PanteonEscolarMyProcess($this->_context,
          $fieldList,
          $this->_titulo_entidade,
          "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
          array(),
          $this->_nome_entidade,
          PanteonEscolarBaseDBAccess::DATABASE());
    }

    else
    {
      $processpage = new PanteonEscolarMyProcess($this->_context,
          $fieldList,
          $this->_titulo_entidade,
          "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
          array($button),
          $this->_nome_entidade,
          PanteonEscolarBaseDBAccess::DATABASE());
    }

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
    $filtro = "";

    $id_instituicao = $this->_context->ContextValue("id_instituicao_filtro");
    $id_turma = $this->_context->ContextValue("id_turma_filtro");

    if($id_instituicao != "")
    {
      $filtro .= " id_instituicao = " . $id_instituicao ." ";
    }

    if($id_turma != "")
    {
      //$filtro .= " id_turma = " . $id_turma ." ";

      $db = new UsuarioXTurmaDB($this->_context);
      $itTurma = $db->obterTodosOsUsuariosPorIDTurma($id_turma);

      if($itTurma->hasNext())
      {
        if($id_instituicao != "")
        {
          $filtro .= " AND ";
        }

        $filtro2 .= " ( id_usuario IN (";

        while($itTurma->hasNext())
        {
          $sr = $itTurma->moveNext();
          $filtro2 .= $sr->getField("id_usuario").", ";

        }

        $filtro2 = rtrim($filtro2, ", ");
        $filtro .= $filtro2." )) ";

      }
    }

    if($this->_context->ContextValue("nome_completo_filtro") != "")
    {
      if($id_instituicao != "")
      {
        $filtro .= " AND ";
      }

      $filtro .= " nome_completo_usuario like '%".$this->_context->ContextValue("nome_completo_filtro")."%' ";

    }


    if($filtro != "")
    {
      $processpage->setFilter($filtro);
    }

    $processpage->setSort("nome_completo_usuario ASC");

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

  public function UsuarioDBXML($context, $nome_modulo = "usuario", $titulo = "Usuário")
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
