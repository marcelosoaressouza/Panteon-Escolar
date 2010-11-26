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

class MeusTemasPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {
    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita") {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");

      $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Meus Temas Panteon');
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Aqui, ficam disponíveis todos os Temas Panteon selecionados na Biblioteca do Tema Panteon.', '');
      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    if($this->_opcao == "processPageField") {
      $nome_modulo = "meustemaspanteon";
      if($this->_context->ContextValue("acao") == 'ppmsgs') $this->_context->redirectUrl($nome_modulo);

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $dbxml = new UsuarioXTemaPanteonDBXML($this->_context, "meustemaspanteon", "Selecione um Tema Panteon para trabalhar");

      $permissao = array(false, false, false, false);
      $pagina = $dbxml->criarProcessPageFields($id_usuario, $permissao);

      if($this->_context->ContextValue("acao") == "limpar") {
        $this->_context->removeCookie("id_tema_panteon_definido");
        $this->_context->removeCookie("nome_tema_panteon_definido");
        $this->_context->redirectUrl("/meustemaspanteon");

      }

      if($this->_context->getCookie("id_tema_panteon_definido") != "") {
        $limpar = "<div id='meusPontosDeVistas'><a href='/meustemaspanteon&acao=limpar'>Limpar Seleção Tema Panteon</a></div><br/>";
        $span1->addXmlnukeObject(new XmlNukeText($limpar));
      }

      if($this->_context->ContextValue("acao") == "setDefault") {

        $idUsuarioXTemaPanteon = $this->_context->ContextValue("valueid");
        $dbUsuarioXTemaPanteon = new UsuarioXTemaPanteonDB($this->_context);
        $modelUsuarioXTemaPanteon = new UsuarioXTemaPanteonModel();
        $modelUsuarioXTemaPanteon = $dbUsuarioXTemaPanteon->obterPorId($idUsuarioXTemaPanteon);

        $dbTemaPanteon = new TemaPanteonDB($this->_context);
        $modelTemaPanteon = new TemaPanteonModel();
        $modelTemaPanteon = $dbTemaPanteon->obterPorId($modelUsuarioXTemaPanteon->getIDTemaPanteon());

        $this->_context->addCookie("id_tema_panteon_definido", $modelUsuarioXTemaPanteon->getIDTemaPanteon());
        $this->_context->addCookie("nome_tema_panteon_definido", $modelTemaPanteon->getNomeTemaPanteon());

        $this->_context->redirectUrl("/meutemapanteon");

      }

      if($pagina->getAllRecords()->Count() > 0) {
        $span1->addXmlnukeObject($pagina);
      } else {
        $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Tema Panteon escolhido.<br/> <a href="/biblioteca">Escolha um Tema na  Biblioteca de Temas Panteon.</a></div>'));
      }

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenuPadrao($node, PanteonEscolarMenu::MeusTemas);

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
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'meuperfil'));
      XmlUtil::AddAttribute($node, "nome_usuario", $msg);
      XmlUtil::AddAttribute($node, "logout", "true");

    }
    //
    // Fim - menu head


    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function MeusTemasPanteonDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>