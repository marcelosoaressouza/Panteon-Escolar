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

class MensagemForumDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{
  /**
   * @var Context
   */
  protected $_context;
  protected $_nome_entidade = "mensagem_forum";
  protected $_nome_modulo = "mensagemforum";
  protected $_titulo_entidade = "Mensagem Forum";
  protected $_num_registros_padrao = 10;

  /**
   * @method Metodo utilizado para listar Topicos e mensagens do Forum
   * @param int $id_forum
   * @param int $id_usuario
   * @param array $permissao
   * @param int $id_mensagem_original
   * @param boolean $respostas
   * @param int $id_usuario_por
   * @return PanteonEscolarMyProcess
   */
  public function criarProcessPageFields($id_forum = "", $id_usuario = "", $permissao = "", $id_mensagem_original = NULL, $respostas = false, $id_usuario_por = "")
  {

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario_por);

    // Inicio Campos da Entidade
    if($this->_context->ContextValue("acao") == "visualizarMensagens" || $this->_context->ContextValue("acao") == "" || $this->_context->ContextValue("acao") == "ppmsgs" || $this->_context->ContextValue("acao") == "move")
    {
      //Para exibicao no grid
      $field = ProcessPageFields::FactoryMinimal("id_usuario", "Autor", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarMensagemForumUsuario($this->_context);
      $fieldList->addProcessPageField($field);

      $field = ProcessPageFields::FactoryMinimal("id_mensagem_forum", "Mensagem", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      $field->editable = true;
      $field->visibleInList = true;

      //Adiciona o formater dependendo se eh mensagem ou topico
      if(intval($id_mensagem_original) == 0)
      {
        $field->editListFormatter = new PanteonEscolarMensagemTopicoForum($this->_context);
      }

      else
      {
        $field->editListFormatter = new PanteonEscolarMensagemForum($this->_context);
      }

      $fieldList->addProcessPageField($field);
    }

    else
    {

      //Para exibicao no formulario e novo item ou de responder mensagem
      $field = ProcessPageFields::FactoryMinimal("nome_mensagem_forum", "Título", 64, true, true);
      $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
      $field->size = 30;
      $fieldList->addProcessPageField($field);

      $field = ProcessPageFields::FactoryMinimal("texto_mensagem_forum", "Mensagem", 1000, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
      $fieldList->addProcessPageField($field);

      $field = ProcessPageFields::FactoryMinimal("id_usuario", "Enviado por", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      $field->defaultValue = $id_usuario_por;
      $field->editListFormatter = new PanteonEscolarNomeUsuarioPorID($this->_context);
      $fieldList->addProcessPageField($field);
    }


    $id_mensagem_original_cookie = $this->_context->getCookie("id_mensagem_forum");

    $field = ProcessPageFields::FactoryMinimal("id_mensagem_resposta", "Mensagem Resposta", 64, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;

    if($this->_context->ContextValue("acao") == "ppnew" && !empty($id_mensagem_original_cookie))
    {
      $field->defaultValue = $id_mensagem_original_cookie;
    }

    else
    {
      $field->defaultValue = $id_mensagem_original;
    }

    $fieldList->addProcessPageField($field);

    //Adicionado cookie para criar nova mensagem do topico, assim ao clicar no Novo item/mensagem sera considerado como resposta do topico
    $this->_context->addCookie("id_mensagem_forum", $id_mensagem_original);

    $field = ProcessPageFields::FactoryMinimal("id_forum", "Forum", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_forum;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_mensagem_forum", "Data/Hora Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_" . $this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    //Adiciona o link de apagar quando o usuario é autor da mensagem ou for ADMINISTRADOR, MEDIADOR, GESTOR
    if($this->_context->ContextValue("acao") == "" || $this->_context->ContextValue("acao") == "ppmsgs")
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarMensagemForumFormatter($this->_context, "mensagem_forum", "panteonescolar.meuforum");
      $fieldList->addProcessPageField($field);
    }

    // Fim dos Campos do ProcessPageFields
    // Inicio Custom Button for Set
    $button = new CustomButtons();

    $button->action = "responderMensagem";
    $button->alternateText = "Responder Mensagem";
    $button->message = "Responder Mensagem";
    $button->multiple = MultipleSelectType::ONLYONE;
    $button->icon = "common/editlist/ic_respondermensagem.gif";
    $button->enabled = true;

//        if (($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR")){
//          $button_excluir->action = "excluir";
//          $button_excluir->alternateText = "Excluir Mensagem";
//          $button_excluir->message = "Excluir Mensagem";
//          $button_excluir->multiple = MultipleSelectType::ONLYONE;
//          $button_excluir->icon = "common/editlist/ic_excluir.gif";
//          $button_excluir->enabled = true;
//        }
    /*
      $button2->action = "visualizarMensagens";
      $button2->alternateText = "Visualizar Mensagens";
      $button2->message = "Visualizar Mensagens";
      $button2->multiple = MultipleSelectType::ONLYONE;
      $button2->icon = "common/editlist/ic_detalhes.gif";
      $button2->enabled = true;
     * *
     */

    $processpage = new PanteonEscolarMyProcess($this->_context,
        $fieldList,
        $this->_titulo_entidade,
        "module:panteonescolar." . $this->_nome_modulo . "&amp;chamada=1",
        array($button/*,$button_excluir ,$button2 */),
        $this->_nome_entidade,
        PanteonEscolarBaseDBAccess::DATABASE());

    //Permissao dos botões e acoes do grid
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

    if($id_forum != "")
    {
      $filtro .= " id_forum = " . $id_forum . " ";
    }

    if($respostas != true)
    {
      if(($id_usuario != "") && ($respostas == false))
      {
        $filtro .= " AND id_usuario = " . $id_usuario . " ";
      }
    }

    //Verifica se nao foi passado o id_mensagem_original para exibir as mensagens topicos
    if(intval($id_mensagem_original) == 0)
    {
      $filtro .= " AND id_mensagem_resposta IS NULL ";
    }

    else
    {

      //Obtem string com os ids das mensagens relacionadas como filhas, netas, bisnetas ... do tópico
      $str_mensagens_filhas = $this->obterMensagensFilhas($id_mensagem_original);
      $array_mensagens_filhas = explode(",",$str_mensagens_filhas);
      //Debug::PrintValue("Mensagens: ",$str_mensagens_filhas);

      //loop para organizar/limpar o array
      foreach($array_mensagens_filhas as $id_mensagem)
      {
        if(!empty($id_mensagem))
        {
          $array_mensagens[] = $id_mensagem;
        }
      }

      //verifica se existe alguma mensagem filha e adiciona para listar
      if(count($array_mensagens)>0)
      {
        $filtro .= " AND id_mensagem_forum IN (" . implode(",",$array_mensagens).") ";
      }

      else
      {
        $filtro .= " AND id_mensagem_resposta = ".$id_mensagem_original;
      }
    }

    if($filtro != "")
    {
      $processpage->setFilter($filtro);
    }

    $processpage->setSort(" data_hora_cadastro_mensagem_forum ASC ");

    return $processpage;
  }


  /**
   * @method Metodo recursivo para retornar string com os codigos das mensagens filhas, netas, bisnetas ... do topico separadas por virgula
   * @param type $id_mensagem
   * @return string $filhos
   */
  private function obterMensagensFilhas($id_mensagem)
  {

    $db = new MensagemForumDB($this->_context);
    $itMensagemForum = $db->obterTodasAsMensagensForumPorIDMensagemResposta($id_mensagem);

    //Percorre o iterator
    while($itMensagemForum->hasNext())
    {
      $sr = $itMensagemForum->moveNext();
      $filhos .= $sr->getField("id_mensagem_forum").",";
      //Busca filhos para o id -chama o metodo de forma recursiva
      $retorno_filhos = $this->obterMensagensFilhas($sr->getField("id_mensagem_forum"));

      if($retorno_filhos !="")
      {
        $filhos .= $retorno_filhos;
      }
    }

    return $filhos;
  }

  public function generateObject($current)
  {
    $span1 = new XmlnukeSpanCollection();
    $span1->addXmlnukeObject($this->criarProcessPageFields());
    $node = XmlUtil::CreateChild($current, $this->_nome_entidade, "");
    $body = XmlUtil::CreateChild($node, "body", "");
    parent::generatePage($body);
  }

  public function MensagemForumDBXML($context, $nome_modulo = "forum", $titulo = "Forum")
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
