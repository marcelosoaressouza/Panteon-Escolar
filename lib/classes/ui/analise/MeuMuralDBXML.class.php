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

class MeuMuralDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $respostas = false;

    if($id_tema_panteon == "") $this->_context->redirectUrl("/meustemaspanteon");

    $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "processPageField") {
      $nome_modulo = "meumural";
      if($this->_context->ContextValue("acao") == 'ppmsgs') $this->_context->redirectUrl($nome_modulo);

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));
      $dbxml = new MuralDBXML($this->_context, "meumural", "Mural");

      $permissao = array(false, false, false, false, false);
      $pagina = $dbxml->criarProcessPageFields($id_usuario, $id_tema_panteon, $permissao);

      if($pagina->getAllRecords()->Count() > 0) {
        $span1->addXmlnukeObject($pagina);

      } else {
        if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1)) {
          $span1->addXmlnukeObject($pagina);
        } else {
          $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Ainda não há Notícia no Mural</div>'));
        }
      }
    }

    if($this->_opcao == "listarDireita") {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Dica Mural");
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Fique Atento! Este mural está reservado para avisos e notícias do professor.', '');
      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::Forum));

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
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'meutemapanteon'));
      XmlUtil::AddAttribute($node, "nome_usuario", $msg);
      XmlUtil::AddAttribute($node, "logout", "true");

    }
    //
    // Fim - menu head


    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function MeuMuralDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>