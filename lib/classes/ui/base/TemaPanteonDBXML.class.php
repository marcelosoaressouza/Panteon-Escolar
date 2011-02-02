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

class TemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "tema_panteon";
  protected $_nome_modulo = "temapanteon";
  protected $_titulo_entidade = "Tema Panteon";
  protected $_num_registros_padrao = 3;

  public function criarProcessPageFields($permissao = "",
                                         $qtd_linhas = 3,
                                         $id_criador = "",
                                         $get_tema_panteon = true,
                                         $criar_midiateca = false,
                                         $publicado = true,
                                         $id_instituicao = "",
                                         $biblioteca = false,
                                         $admin = "")
  {

    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new MetodoAnaliseDB($this->_context);
    $it = $db->obterTodos();
    $arrayMetodoAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_metodo_analise", "nome_metodo_analise");

    $db = new EstruturaSocialDB($this->_context);
    $it = $db->obterTodos();
    $arrayEstruturaSocial = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_estrutura_social", "nome_estrutura_social");

    $db = new UsuarioDB($this->_context);
    $it = $db->obterTodos();
    $arrayUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_usuario", "nome_completo_usuario");

    $arraySimNao = array(0 => "Não", 1 => "Sim");
    //
    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    $field = ProcessPageFields::FactoryMinimal("nome_tema_panteon", "Tema", 55, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Provocação", 1, true, true);
      $field->editListFormatter = new PanteonEscolarTemaPanteonFormatter($this->_context, "view");
      $field->editable = false;
      $fieldList->addProcessPageField($field);

    }

    else
    {

      $field = ProcessPageFields::FactoryMinimal("descricao_tema_panteon", "Provocação", 256, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $field->editListFormatter = new PanteonEscolarTextoFormatter();
      $fieldList->addProcessPageField($field);

    }

    $field = ProcessPageFields::FactoryMinimal("id_metodo_analise", "Método Análise", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayMetodoAnalise;

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field->editListFormatter = new PanteonEscolarMetodoAnaliseFormatter($this->_context, "view");
    }

    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_estrutura_social", "Estrutura Social", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayEstruturaSocial;

    if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
    {
      $field->editListFormatter = new PanteonEscolarEstruturaSocialFormatter($this->_context, "view");
    }

    $fieldList->addProcessPageField($field);

    if($biblioteca == false)
    {
      $field = ProcessPageFields::FactoryMinimal("publicado_tema_panteon", "Publicado", 4, true, true);
      $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
      $field->arraySelectList = $arraySimNao;
      $fieldList->addProcessPageField($field);

      $field = ProcessPageFields::FactoryMinimal("privado_tema_panteon", "Privado para Instituição", 4, true, true);
      $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
      $field->arraySelectList = $arraySimNao;
      $fieldList->addProcessPageField($field);

      if($this->_context->ContextValue("acao") == "")
      {
        if($permissao[4] == true)
        {
          $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
          $field->editable = false;
          $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "tema_panteon", "criartemapanteon");
          $fieldList->addProcessPageField($field);
        }

      }

    }

    if($get_tema_panteon == true)
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Selecionar Tema Panteon", 1, true, true);
      $field->editable = false;

      if(($this->_context->ContextValue("acao") == "") || ($this->_context->ContextValue("acao") == "move"))
      {
        $field->editListFormatter = new PanteonEscolarTemaPanteonFormatter($this->_context, "coletarBiblioteca");
      }

      $fieldList->addProcessPageField($field);
    }

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Criador", 16, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->arraySelectList = $arrayUsuario;
    $field->defaultValue = $id_criador;
    $fieldList->addProcessPageField($field);


    $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_tema_panteon", "Data/Hora Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_hora_atualizacao_tema_panteon", "Data/Hora Atualização", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields


    if($criar_midiateca == true)
    {
      $button1 = new CustomButtons();
      $button2 = new CustomButtons();
      $button3 = new CustomButtons();
      $button4 = new CustomButtons();
      $button5 = new CustomButtons();
      $button6 = new CustomButtons();

      $button1->action = "criarMidiatecaTemaPanteon";
      $button1->alternateText = "Anexar Mídia";
      $button1->message = "Anexar Mídia";
      $button1->multiple = MultipleSelectType::ONLYONE;
      $button1->icon = "common/editlist/ic_mural.gif";
      $button1->enabled = true;

      $button2->action = "criarSituacaoProblema";
      $button2->alternateText = "Criar Situações-Problemas";
      $button2->message = "Criar Situações-Problemas";
      $button2->multiple = MultipleSelectType::ONLYONE;
      $button2->icon = "common/editlist/ic_criarsituacaoproblema.gif";
      $button2->enabled = true;

      $button3->action = "criarPontoDeVista";
      $button3->alternateText = "Criar Sujeito(s) e Ponto(s) de Vista";
      $button3->message = "Criar Ponto(s) de Vista";
      $button3->multiple = MultipleSelectType::ONLYONE;
      $button3->icon = "common/editlist/ic_criarpontodevista.gif";
      $button3->enabled = true;

      $button4->action = "criarForum";
      $button4->alternateText = "Criar Forum";
      $button4->message = "Criar Forum";
      $button4->multiple = MultipleSelectType::ONLYONE;
      $button4->icon = "common/editlist/ic_criarforum.gif";
      $button4->enabled = true;

      $button5->action = "criarGrupo";
      $button5->alternateText = "Criar Grupo(s) de Usuários";
      $button5->message = "Criar Grupo(s) de Usuários";
      $button5->multiple = MultipleSelectType::ONLYONE;
      $button5->icon = "common/editlist/ic_grupo.gif";
      $button5->enabled = true;

      $button6->action = "criarMural";
      $button6->alternateText = "Criar Mensagem no Mural";
      $button6->message = "Criar Mensagem no Mural";
      $button6->multiple = MultipleSelectType::ONLYONE;
      $button6->icon = "common/editlist/ic_criarmidiatecatemapanteon.gif";
      $button6->enabled = true;

      $button_array = array($button2, $button3, $button1, $button5, $button6);
    }


    if($criar_midiateca  == false)
    {
      unset($button_array[1]);
      unset($button_array[2]);
      unset($button_array[3]);
      unset($button_array[4]);
    }

    // Inicio Custom Button for Set

    $processpage = new PanteonEscolarMyProcess($this->_context,
        $fieldList,
        $this->_titulo_entidade,
        "module:panteonescolar.".$this->_nome_modulo.'&amp;chamada=1',
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

    $processpage->setPageSize($qtd_linhas, $this->_context->ContextValue("curpage"));

    // Filtros
    $filtro = " ";
    $id_metodo_analise   = $this->_context->ContextValue("id_metodo_analise_filtro");
    $id_estrutura_social = $this->_context->ContextValue("id_estrutura_social_filtro");
    $descricao_tema_panteon = $this->_context->ContextValue("descricao_tema_panteon_filtro");

    if($admin == false)
    {
      if($publicado == "true")
      {
        $filtro .= " publicado_tema_panteon = "  . $publicado           ."  ";

        if($id_criador          != "")
        {
          $filtro .= " AND id_usuario = "          . $id_criador          ." ";
        }
      }

      if($id_criador != "")
      {
        $filtro .= " id_usuario = " . $id_criador ." ";
      }

      if(($id_metodo_analise   != "") && ($id_metodo_analise != "All"))
      {
        $filtro .= " AND id_metodo_analise = "   . $id_metodo_analise   ." ";

      }

      if(($id_estrutura_social != "") && ($id_estrutura_social != "All"))
      {
        $filtro .= " AND id_estrutura_social = " . $id_estrutura_social ." ";

      }

      if($descricao_tema_panteon != "")
      {
        $filtro .= " AND descricao_tema_panteon LIKE '%" . $descricao_tema_panteon ."%'";

      }

      if($id_instituicao != "")
      {
        $filtro = "";

        $db = new UsuarioDB($this->_context);
        $itUsuario = $db->obterTodosOsUsuariosPorIDInstituicao($id_instituicao);

        while($itUsuario->hasNext())
        {
          $sr = $itUsuario->moveNext();
          $filtro .= " id_usuario = ".$sr->getField("id_usuario");

          if($itUsuario->hasNext())
          {
            $filtro .= " OR ";
          }

        }
      }

      if($this->_context->ContextValue("acao") != "ppedit")
      {
        $processpage->setFilter($filtro);
        $processpage->setSort("data_hora_cadastro_tema_panteon DESC");

      }

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

  public function TemaPanteonDBXML($context, $nome_modulo = "temapanteon", $titulo = "Tema Panteon")
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
