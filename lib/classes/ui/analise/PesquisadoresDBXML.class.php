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

class PesquisadoresDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    if($id_tema_panteon == "") $this->_context->redirectUrl("/meustemaspanteon");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    // Inicio
    //
    if(($this->_opcao == "mostrarPesquisadores") && ($this->_context->ContextValue("action") == "")) {
      $dbxml = new UsuarioXTemaPanteonDBXML($this->_context, "pesquisadores", "Pesquisadores");

      $permissao = array(false, false, false, false);
      $pagina = $dbxml->criarProcessPageFields("", $permissao, 8, $id_tema_panteon);
      $span1->addXmlnukeObject($pagina);

    }
    //
    // Fim

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

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario());
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
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso));
      XmlUtil::AddAttribute($node, "nome_usuario", $msg);
      XmlUtil::AddAttribute($node, "logout", "true");

    }
    //
    // Fim - menu head


    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function PesquisadoresDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>