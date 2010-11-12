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

class MeuForumMensagemDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
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
      $span1->addXmlnukeObject(new XmlNukeText('<br/><div id="meusPontosDeVistas"> <a href="/meumural"> Mural <img class="texto_alinhado" src="/static/images/icones/Mural.png" alt="Mural" title="Mural"/> </a> </div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<br/><div id="meusPontosDeVistas"> <a href="common/3rdparty/ajaxchat/" onclick="openWindow(this.href);this.blur();return false;"><img class="texto_alinhado" src="/static/images/icones/Bate-Papo.png" alt="Bate-Papo" title="Bate-Papo"/> Bate-Papo Web </a> </div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<br/><div id="meusPontosDeVistas"> <a href="/meuforum"> Fórum <img class="texto_alinhado" src="/static/images/icones/Forum.png" alt="Fórum" title="Fórum"/></a> </div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<br/><div id="meusPontosDeVistas"> <a href="/minhasmensagens"><img class="texto_alinhado" src="/static/images/icones/Mensagem.png" alt="Mensagem" title="Mensagem"/> Mensagens </a> </div>'));

    }
    if($this->_opcao == "listarDireita") {
      $db = new UsuarioXTemaPanteonDB($this->_context);
      $itDB = $db->obterTodosOsUsuariosPorIDTemaPanteon($id_tema_panteon, 5);

      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Pesquisadores");

      if($itDB->Count() > 0)
        $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "nome_completo_usuario", "nome_instituicao", "");
      else
        $body = PanteonEscolarBaseModule::preencherBarraVazia($node);
      XmlUtil::AddAttribute($node, "link_info", '<a href="pesquisadores">Clique aqui para ver lista completa de Pesquisadores</a>');

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

  public function filtro() {
    $span = new XmlnukeSpanCollection();
    $formPost = "module:panteonescolar.MeuForumMensagem";
    $form = new XmlFormCollection($this->_context, $formPost, "Meu Forum");

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Filtrar");

    $form->addXmlnukeObject($this->filtroMeuForumMensagem());
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroMeuForumMensagem() {
    $listaMeuForumMensagem = array(1 => "Minhas Mensagens", 2 => "Todos as Mensagens", 3 => "Respostas as Minhas Mensagens");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_meu_forum_filtro", "Meu Forum", $listaMeuForumMensagem);

    return $lista;

  }

  public function MeuForumMensagemDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>