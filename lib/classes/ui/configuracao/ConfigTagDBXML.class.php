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

class ConfigTagDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {
    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    $titulo = "Palavras-Chaves";
    $nome_modulo = "configtag";

    // Inicio - Area Principal
    //
    if($this->_opcao == "processPageField")
    {
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $dbUsuarioXNivelAcesso = new UsuarioXNivelAcessoDB($this->_context);
      $nivel_acesso = $dbUsuarioXNivelAcesso->obterNivelAcessoPorIDUsuario($id_usuario);

      if($this->_context->ContextValue("acao") == "delete")
      {
        $db = new TagDB($this->_context);
        $db->excluir($this->_context->ContextValue("id"));
        $this->_context->addCookie("mensagem_aviso", "Tag Excluida");
        $this->_context->redirectUrl("/configtag");

      }

      if($nivel_acesso == "GESTOR" || $nivel_acesso == "MEDIADOR")
      {
        $dbUsuario = new UsuarioDB($this->_context);
        $id_instituicao = $dbUsuario->obterPorId($id_usuario)->getIDInstituicao();

      }

      else if($nivel_acesso == "ADMINISTRADOR")
      {
        $id = "";
      }

      else
      {
        $this->_context->redirectUrl("/meuperfil");
        $id = "";

      }

      $permissao = array(false, false, true, false);

      $dbxml = new TagDBXML($this->_context, $nome_modulo, $titulo);

      if($this->_context->ContextValue("nome_tag") != "")
      {
        $db = new TagDB($this->_context);

        if($db->verDuplicado($this->_context->ContextValue("nome_tag")) > 0)
        {
          $this->_context->addCookie("mensagem_aviso", "Palavra-Chave já cadastrada.");
          $this->_context->redirectUrl("/configtag");
        }

        else
        {
          $pagina = $dbxml->criarProcessPageFields($permissao);

        }

      }

      else
      {
        $pagina = $dbxml->criarProcessPageFields($permissao);
      }

      if($pagina->getAllRecords()->Count() > 0)
      {
        $span1->addXmlnukeObject($pagina);

      }

      else
      {
        $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma Tag cadastrada.</div>'));

      }

    }

    //
    // Fim - Area Principal

    // Inicio Lista Direita
    //
    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraVazia($node);

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    //
    // Fim - Lista Direita

    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuConfig(PanteonEscolarMenu::Tag, $nivel_acesso));

    }

    //
    // Fim - menu

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead")
    {
      $nodeHead = XmlUtil::CreateChild($current, "blockhead", "");
      XmlUtil::AddAttribute($nodeHead, "perfil", strtolower($nivel_acesso));

      $msg = "Bem-Vindo, ".ucfirst($this->_context->authenticatedUser())." (".$nivel_acesso.").";
      $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'configinstituicao'));
      XmlUtil::AddAttribute($node, "nome_usuario", $msg);
      XmlUtil::AddAttribute($node, "logout", "true");

    }

    //
    // Fim - menu head

    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function filtro()
  {
    $span = new XmlnukeSpanCollection();
    $formPost = "module:panteonescolar.ConfigTag";
    $form = new XmlFormCollection($this->_context, $formPost, "Minhas Mensagens");

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Filtrar");

    $form->addXmlnukeObject($this->filtroConfigTag());
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroConfigTag()
  {
    $listaConfigTag = array(1 => "Mensagens Enviadas por Mim", 2 => "Mensagens Recebidas");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_minhas_mensagens_filtro", "Minhas Mensagens", $listaConfigTag);

    return $lista;

  }

  public function ConfigTagDBXML($context, $opcao)
  {
    if(!($context instanceof Context))
    {
      throw new Exception("Falta de Context");
    }

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>