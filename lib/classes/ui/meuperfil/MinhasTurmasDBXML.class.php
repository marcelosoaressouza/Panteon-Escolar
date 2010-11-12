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

class MinhasTurmasDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {
    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    $db = new UsuarioDB($this->_context);
    $id_instituicao = $db->obterPorId($id_usuario)->getIDInstituicao();

    if($this->_opcao == "processPageField") {
      $dbxml = new UsuarioXTurmaDBXML($this->_context, "minhasturmas", "Minhas Turmas");

      $permissao = array(false, false, false, false);
      $pagina = $dbxml->criarProcessPageFields(NULL, $id_instituicao, $permissao, $id_usuario);

      if($pagina->getAllRecords()->Count() > 0)
        $span1->addXmlnukeObject($pagina);
      else
        $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Você ainda não faz parte de uma Turma.<br/>Para participar peça permissão ao seu professor enviando uma mensagem na <a href="/meuforummensagem">Sala de Encontro</a>.</div>'));

      $node = XmlUtil::CreateChild($current, "blockcenter", "");
      $body = XmlUtil::CreateChild($node, "body", "");

    }

    // Inicio - Lista Direita
    //
    if($this->_opcao == "listarDireita") {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");

      $db = new UsuarioXTurmaDB($this->_context);
      $id_usuario = $this->_context->authenticatedUserId();
      $itDB = $db->obterTodasAsTurmasPorIDUsuario($id_usuario);

      $body = PanteonEscolarBaseModule::criarTitulo($node, "Minhas Turmas");

      if($itDB->Count() > 0)
        $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "nome_turma");
      else
        $body = PanteonEscolarBaseModule::preencherBarraVazia($node);

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }
    //
    // Fim - Lista Direita

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenuPadrao($node, PanteonEscolarMenu::Turmas);
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


    parent::generatePage($body);

  }

  public function MinhasTurmasDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>