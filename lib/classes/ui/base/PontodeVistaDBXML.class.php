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

class PontodeVistaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "ponto_de_vista";
  protected $_nome_modulo = "pontodevista";
  protected $_titulo_entidade = "Ponto de Vista";
  protected $_num_registros_padrao = 3;

  public function criarProcessPageFields($id_sujeito = "", $permissao = "", $id_usuario = "", $coletado = "", $id_tema_panteon = "")
  {

    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas

    $db = new SujeitoDB($this->_context);
    $it = $db->obterTodos();
    $arraySujeito = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_sujeito", "nome_sujeito");

    $db = new SituacaoProblemaDB($this->_context);
    $it = $db->obterTodos();
    $arraySituacaoProblema = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_situacao_problema", "nome_situacao_problema");

    $db = new ItemAnaliseDB($this->_context);
    $it = $db->obterTodos();
    $arrayItemAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_item_analise", "nome_item_analise");

    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio dos Campos do ProcessPageFields

    // Inicio Campos da Entidade

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move") || ($this->_context->ContextValue("acao") == "verPontoDeVista"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Sujeito", 1, true, true);
      $field->editListFormatter = new PanteonEscolarPontoDeVistaFormatter($this->_context, "sujeito");
      $field->editable = false;
      $fieldList->addProcessPageField($field);

      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Ponto de Vista", 1, true, true);
      $field->editListFormatter = new PanteonEscolarPontoDeVistaFormatter($this->_context, "view");
      $field->editable = false;
      $fieldList->addProcessPageField($field);

    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("texto_ponto_de_vista", "Ponto de Vista", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("id_item_analise", "Item Ánalise", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayItemAnalise;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_situacao_problema", "Situação Problema", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arraySituacaoProblema;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Coletar", 1, true, true);
    $field->editListFormatter = new PanteonEscolarPontoDeVistaFormatter($this->_context, 'coletar', $id_usuario);
    $field->editable = false;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Descartar", 1, true, true);
    $field->editListFormatter = new PanteonEscolarPontoDeVistaFormatter($this->_context, 'descartar', $id_usuario);
    $field->editable = false;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_sujeito", "Sujeito", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arraySujeito;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_ponto_de_vista", "Data Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields

    $button1 = new CustomButtons();
    $button1->action = "minhaMidiateca";
    $button1->alternateText = "Anexar Mídia";
    $button1->message = "Anexar Mídia";
    $button1->multiple = MultipleSelectType::ONLYONE;
    $button1->icon = "common/editlist/ic_mural.gif";
    $button1->enabled = true;

    $button_array = array($button1);

    $processpage = new PanteonEscolarMyProcess($this->_context,
        $fieldList,
        $this->_titulo_entidade,
        "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
        $button_array,
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
    $filtro = "";

    $id_item_analise      = $this->_context->ContextValue("id_item_analise_filtro");
    $id_situacao_problema = $this->_context->ContextValue("id_situacao_problema_filtro");
    $texto_ponto_de_vista_filtro = $this->_context->ContextValue("texto_ponto_de_vista_filtro");

    if($id_tema_panteon != "")
    {
      $dbSujeito = new SujeitoDB($this->_context);
      $itSujeito = $dbSujeito->obterTodosOsSujeitosPorIDTemaPanteon($id_tema_panteon);

      $filtro .= " ( ";

      while($itSujeito->hasNext())
      {
        $sr = $itSujeito->moveNext();
        $id_sujeito_tema_panteon = $sr->getField('id_sujeito');
        $filtro .= " id_sujeito = " . $id_sujeito_tema_panteon;

        if($itSujeito->hasNext())
        {
          $filtro .= " OR ";
        }

      }

      $filtro .= " ) ";

    }

    if($id_sujeito != "")
    {
      $filtro .= " id_sujeito = " . $id_sujeito;

    }

    if($coletado == true)
    {
      $db = new UsuarioXPontoDeVistaDB($this->_context);
      $itPontoDeVistaColetado = $db->obterTodosOsPontoDeVistaPorIDUsuario($id_usuario);

      if($itPontoDeVistaColetado->hasNext())
      {
        if($id_sujeito != "")
        {
          $filtro .= " AND ";
        }

        if($id_tema_panteon != "")
        {
          $filtro .= " AND ";
        }

        $filtro .= " ( ";

        while($itPontoDeVistaColetado->hasNext())
        {
          $sr = $itPontoDeVistaColetado->moveNext();
          $filtro .= " id_ponto_de_vista <> " . $sr->getField("id_ponto_de_vista")."";

          if($itPontoDeVistaColetado->hasNext())
          {
            $filtro .= " AND ";
          }

        }

        $filtro .= " ) ";

      }
    }

    if(($id_item_analise != "") && ($id_item_analise != "All"))
    {
      // if($id_sujeito != "")
      $filtro .= " AND ";
      $filtro .= " id_item_analise = " . $id_item_analise;

    }

    if(($id_situacao_problema != "") && ($id_situacao_problema != "All"))
    {
      // if(($id_tema_panteon != "") ||($id_sujeito != "") || (($id_item_analise != "") && ($id_item_analise != "All")))
      $filtro .= " AND ";
      $filtro .= " id_situacao_problema = " . $id_situacao_problema;

    }

    if($texto_ponto_de_vista_filtro != "")
    {
      $filtro .= " AND ";
      $filtro .= "texto_ponto_de_vista LIKE '%" . $texto_ponto_de_vista_filtro."%'";

    }

    if($filtro != "")
    {
      $processpage->setFilter($filtro);
    }

    // Debug::PrintValue($filtro);

    $processpage->setPageSize($this->_num_registros_padrao, $this->_context->ContextValue("curpage"));

    return $processpage;

  }

  // Ponto de Vista do Sujeito
  public function criarProcessPageFieldsSujeito($id_sujeito = "", $id_tema_panteon = "", $permissao = "")
  {

    $db = new TemaPanteonDB($this->_context);
    $id_metodo_analise = $db->obterPorId($id_tema_panteon)->getIDMetodoAnalise();

    //Debug::PrintValue($id_sujeito.' - '.$id_tema_panteon);
    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas

    $db = new SituacaoProblemaDB($this->_context);
    $it = $db->obterTodasAsSituacoesProblemasPorIDTemaPanteon($id_tema_panteon);
    $arraySituacaoProblema = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_situacao_problema", "nome_situacao_problema");

    $db = new ItemAnaliseDB($this->_context);
    $it = $db->obterTodosOsItensAnalisePorIDMetodoAnalise($id_metodo_analise);
    $arrayItemAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_item_analise", "nome_item_analise");

    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio dos Campos do ProcessPageFields

    // Inicio Campos da Entidade

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move") || ($this->_context->ContextValue("acao") == "verPontoDeVista"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Ponto de Vista", 1, true, true);
      $field->editListFormatter = new PanteonEscolarPontoDeVistaFormatter($this->_context, "view");
      $field->editable = false;
      $fieldList->addProcessPageField($field);

    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("texto_ponto_de_vista", "Ponto de Vista", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("id_item_analise", "Item Ánalise", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayItemAnalise;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_situacao_problema", "Situação Problema", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arraySituacaoProblema;
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "ponto_de_vista", "criarpontodevistasujeitotemapanteon");
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_ponto_de_vista", "Data Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_sujeito", "Sujeito", 30, false, required);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_sujeito;
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Custom Button for Set
    $button = new CustomButtons();

    if($permissao[4] == true)
    {
      $button->action = "coletarPontoDeVista";
      $button->alternateText = "Coletar Ponto de Vista";
      $button->message = "Coletar Ponto de Vista";
      $button->multiple = MultipleSelectType::ONLYONE;
      $button->icon = "common/editlist/ic_coletar_ponto_de_vista.gif";
      $button->enabled = true;
    }

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
    if($id_sujeito != "")
    {
      $processpage->setFilter("id_sujeito = " . $id_sujeito);
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

  public function PontodeVistaDBXML($context, $nome_modulo = "usuarioxturma", $titulo = "Usuário X Turma")
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
