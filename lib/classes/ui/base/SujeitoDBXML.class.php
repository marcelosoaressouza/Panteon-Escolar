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

class SujeitoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "sujeito";
  protected $_nome_modulo = "sujeito";
  protected $_titulo_entidade = "Sujeito";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_tema_panteon, $permissao = "")
  {

    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new TemaPanteonDB($this->_context);
    $it = $db->obterTodos();
    $arrayTemaPanteon = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                        "id_tema_panteon",
                        "nome_tema_panteon");

    $db = new GrupoSocialDB($this->_context);
    $it = $db->obterTodos();
    $arrayGrupoSocial = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                        "id_grupo_social",
                        "nome_grupo_social");

    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();
    $fileUpload = new ProcessPageStateBaseSaveFormatterFileUpload($this->_context, "upload/sujeito");
    $fileUpload->resizeImageTo(85, 85);

    // Inicio dos Campos do ProcessPageFields

    // Inicio Campos da Entidade
    $field = ProcessPageFields::FactoryMinimal("caminho_foto_sujeito", "Foto", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::FILEUPLOAD;
    $field->saveDatabaseFormatter = $fileUpload;
    $field->editListFormatter = new PanteonEscolarUploadFormatter();
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("nome_sujeito", "Nome", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("acao") == "ppedit") || ($this->_context->ContextValue("chamada") == 1))
    {
      $field = ProcessPageFields::FactoryMinimal("descricao_sujeito", "Descrição", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $fieldList->addProcessPageField($field);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Detalhe", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarSujeitoFormatter($this->_context, "view");
      $fieldList->addProcessPageField($field);
    }

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayTemaPanteon;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_grupo_social", "Grupo Social", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayGrupoSocial;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Ver Ponto de Vista", 1, true, true);
    $field->editListFormatter = new PanteonEscolarPontoDeVistaSujeitoFormatter($this->_context);
    $field->editable = false;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_cadastro_sujeito", "Data Cadastro", 15, false, false);
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

    $filtro = "";

    $id_grupo_social_filtro  = $this->_context->ContextValue("id_grupo_social_filtro");

    if($id_tema_panteon !="")
    {
      $filtro .= " id_tema_panteon = "  . $id_tema_panteon ." ";
    }

    if($id_grupo_social_filtro != "")
    {
      if($id_tema_panteon !="")
      {
        $filtro .= " AND ";
      }

      $filtro .= " id_grupo_social = ". $id_grupo_social_filtro . " ";

    }

    if($id_grupo_social_filtro == 2048)
    {
      $filtro = "";

    }

    if($filtro != "")
    {
      $processpage->setFilter($filtro);
    }

    if($this->_context->ContextValue("caminho_foto_sujeito") != "")
    {
      $fileProcessor = new UploadFilenameProcessor('*.*', $this->_context);
      $fileProcessor->setFilenameLocation(ForceFilenameLocation::DefinePath, "upload/sujeito");
      $resultUpload = $this->_context->processUpload($fileProcessor, false, 'caminho_foto_sujeito');

    }

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

    return $processpage;

  }

  // Usado para Criação de Sujeito/Ponto de Vista
  public function criarProcessPageFieldsTemaPanteon($id_tema_panteon, $id_estrutura_social, $permissao = "")
  {

    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas

    $db = new GrupoSocialDB($this->_context);
    $it = $db->obterTodosOsGruposSociaisPorIDEstruturaSocial($id_estrutura_social);
    $arrayGrupoSocial = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_grupo_social", "nome_grupo_social");

    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();
    $fileUpload = new ProcessPageStateBaseSaveFormatterFileUpload($this->_context, "upload/sujeito");
    $fileUpload->resizeImageTo(85, 85);

    // Inicio dos Campos do ProcessPageFields
    $field = ProcessPageFields::FactoryMinimal("caminho_foto_sujeito", "Foto do Sujeito", 30, true, false);
    $field->fieldXmlInput = XmlInputObjectType::FILEUPLOAD;
    $field->saveDatabaseFormatter = $fileUpload;
    $field->editListFormatter = new PanteonEscolarUploadFormatter();
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("nome_sujeito", "Nome Sujeito", 45, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("acao") == "ppedit") || ($this->_context->ContextValue("chamada") == 1))
    {
      $field = ProcessPageFields::FactoryMinimal("descricao_sujeito", "Descrição", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $fieldList->addProcessPageField($field);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Detalhe", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarSujeitoFormatter($this->_context, "view");
      $fieldList->addProcessPageField($field);
    }

    $field = ProcessPageFields::FactoryMinimal("id_grupo_social", "Grupo Social do Sujeito", 16, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayGrupoSocial;
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "sujeito", "criarpontodevistatemapanteon");
      $fieldList->addProcessPageField($field);

    }

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_tema_panteon;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_cadastro_sujeito", "Data Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d");
    $fieldList->addProcessPageField($field);

    // Custom Button for Set
    $button = new CustomButtons();
    $button->action = "criarPontoDeVistaSujeito";
    $button->alternateText = "Criar Ponto de Vista do Sujeito";
    $button->message = "Criar Ponto de Vista do Sujeito";
    $button->multiple = MultipleSelectType::ONLYONE;
    $button->icon = "common/editlist/ic_criarpontodevistasujeito.gif";
    $button->enabled = true;

    // Fim dos Campos do ProcessPageFields

    $processpage = new PanteonEscolarMyProcess($this->_context,
        $fieldList,
        $this->_titulo_entidade,
        "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
        array($button),
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
    if($id_tema_panteon != "")
    {
      $processpage->setFilter("id_tema_panteon = " . $id_tema_panteon);
    }

    if($this->_context->ContextValue("caminho_foto_sujeito") != "")
    {
      $fileProcessor = new UploadFilenameProcessor('*.*', $this->_context);
      $fileProcessor->setFilenameLocation(ForceFilenameLocation::DefinePath, "upload/sujeito");
      $resultUpload = $this->_context->processUpload($fileProcessor, false, 'caminho_foto_sujeito');

    }

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

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

  public function SujeitoDBXML($context, $nome_modulo = "sujeito", $titulo = "Sujeito")
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
