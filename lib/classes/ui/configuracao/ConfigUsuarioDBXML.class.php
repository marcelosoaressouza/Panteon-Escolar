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

class ConfigUsuarioDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {
    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    $titulo = "Usuário";
    $nome_modulo = "configusuario";

    // Inicio - Area Principal
    //
    if($this->_opcao == "processPageField") {
      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $nome_modulo = "configusuario";
      if($this->_context->ContextValue("acao") == 'ppmsgs') $this->_context->redirectUrl($nome_modulo);

      if($this->_context->ContextValue("acao") == "delete") {
        $db = new UsuarioDB($this->_context);
        $db->excluir($this->_context->ContextValue("id"));
        $this->_context->addCookie("mensagem_aviso", "Usuário excluido");
        $this->_context->redirectUrl("/configusuario");

      }

      if($this->_context->ContextValue("acao") == "configNivelAcesso") {
        $this->_context->addCookie("id_usuario_x_nivel_acesso_selecionado", $this->_context->ContextValue("valueid"));
        $this->_context->redirectUrl("/configusuarioxnivelacesso");

      }

      $dbUsuarioXNivelAcesso = new UsuarioXNivelAcessoDB($this->_context);
      $nivel_acesso = $dbUsuarioXNivelAcesso->obterNivelAcessoPorIDUsuario($id_usuario);

      $permissao = array(true, false, true, false);

      if($nivel_acesso == "GESTOR") {
        $dbUsuario = new UsuarioDB($this->_context);
        $id_instituicao = $dbUsuario->obterPorId($id_usuario)->getIDInstituicao();

      } else if($nivel_acesso == "ADMINISTRADOR") {
        $id = "";
      } else {
        $this->_context->redirectUrl("/meuperfil");
        $id = "";

      }

      $dbxml = new UsuarioDBXML($this->_context, $nome_modulo, $titulo);
      $pagina = $dbxml->criarProcessPageFields($id_instituicao, $permissao);

      if($pagina->getAllRecords()->Count() > 0) {
        if($this->_context->ContextValue("acao") == "") $span1->addXmlnukeObject($this->filtro());
        $span1->addXmlnukeObject($pagina);

      } else {
        $aviso = new XmlInputLabelObjects("<p></p>");
        $aviso->addXmlnukeObject(new XmlNukeText('<div class="CaixaAviso">Nenhum Usuário encontrado.</div>'));
        $span1->addXmlnukeObject($aviso);
        if($this->_context->ContextValue("acao") == "") $span1->addXmlnukeObject($this->filtro());

      }

    }
    //
    // Fim - Area Principal

    if($this->_opcao == "listarDireita") {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraVazia($node);
      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuConfig(PanteonEscolarMenu::Usuario, $nivel_acesso));

    }
    //
    // Fim - menu

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead") {
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

  public function filtro() {
    $span = new XmlnukeSpanCollection();
    $formPost = "module:panteonescolar.ConfigUsuario";
    $form = new XmlFormCollection($this->_context, $formPost, "Minhas Mensagens");
    $form->addXmlnukeObject($this->filtroConfigUsuario());

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroConfigUsuario() {
    return new XmlInputTextBox("Texto: ", "nome_completo_filtro", NULL, 40);

  }

  public function ConfigUsuarioDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>