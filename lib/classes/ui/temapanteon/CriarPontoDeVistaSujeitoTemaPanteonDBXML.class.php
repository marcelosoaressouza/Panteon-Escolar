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

class CriarPontoDeVistaSujeitoTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {
    $id_sujeito_tema_panteon = $this->_context->getCookie("id_sujeito_tema_panteon");
    $id_tema_panteon_ponto_de_vista = $this->_context->getCookie("id_tema_panteon_ponto_de_vista");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita") {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Ponto de Vista');
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Aqui, você pode cadastrar os pontos de vista dos sujeitos sobre as situações-problemas. No cadastro você precisa definir a qual situação-problema e item de análise este ponto de vista está relacionado.', '');

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    if($this->_opcao == "processPageField") {
      $span = new XmlnukeSpanCollection();
      $nome_modulo = "criarpontodevistasujeitotemapanteon";
      if($this->_context->ContextValue("acao") == 'ppmsgs') $this->_context->redirectUrl($nome_modulo);

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $aviso = new XmlInputLabelObjects("<p></p>");
      $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas"> <a href="/criarpontodevistatemapanteon">Clique aqui para retornar a Criação de Sujeito deste Tema Panteon </a></div>'));

      if(($this->_context->ContextValue("acao") != "ppnew")  &&
          ($this->_context->ContextValue("acao") != "ppedit") &&
          ($this->_context->ContextValue("chamada") != 1)) $span1->addXmlnukeObject($aviso);
      $span1->addXmlnukeObject(new XmlNukeBreakLine());

      $db = new SujeitoDB($this->_context);
      $titulo = "Ponto de Vista de ".$db->obterPorId($id_sujeito_tema_panteon)->getNomeSujeito();

      $dbxml = new PontoDeVistaDBXML($this->_context, "criarpontodevistasujeitotemapanteon", $titulo);

      // permissao - $newRec, $view, $edit, $delete, $outros
      $permissao = array(true, false, true, false, false);
      $pagina = $dbxml->criarProcessPageFieldsSujeito($id_sujeito_tema_panteon, $id_tema_panteon_ponto_de_vista, $permissao);

      if($pagina->getAllRecords()->Count() > 0) {
        $span1->addXmlnukeObject($pagina);

      } else {

        if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1)) {
          $span1->addXmlnukeObject($pagina);

        } else {
          $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Ponto de Vista encontrado, cadastre o primeiro agora.<br/> <a href="'. PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clique Aqui</a></div>'));

        }

      }


    }

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuCriarTemaPanteon(PanteonEscolarMenu::CriarTema));

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

  public function CriarPontoDeVistaSujeitoTemaPanteonDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>