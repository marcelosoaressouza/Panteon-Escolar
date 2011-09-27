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

class MinhaPropostaDeAcaoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {
    $id_usuario = $this->_context->authenticatedUserId();
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);


    if($this->_opcao == "processPageField")
    {
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));
      $url_link = $this->_context->bindModuleUrl("panteonescolar.minhaspropostasdeacao");
      $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas"><a href="' . $url_link . '"> Proposta de Ação detalhada.</a><br/></div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<br/>'));
      $url_link = $this->_context->bindModuleUrl("panteonescolar.minhapropostadeacaogeral");
      $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas"><a href="' . $url_link . '">Proposta de Ação Geral. </a></div>'));
    }

    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::PlanoDeAcao));
    }

    //
    // Fim - menu

    if($this->_opcao == "listarDireita")
    {
      $db = new DiagnosticoIndividualDB($this->_context);
      $dbUsuarioXTemaPanteon = new UsuarioXTemaPanteonDB($this->_context);
      $itDB = $db->obterTodosOsDiagnosticosIndividuaisPorIDIUsuarioXTemaPanteon($dbUsuarioXTemaPanteon->obterIDPorIDUsuarioXIDTemaPanteon($id_usuario, $id_tema_panteon), 5);
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Diagnósticos Realizados");

      if($itDB->Count() > 0)
      {
        $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "nome_situacao_problema", "texto_diagnostico_individual", "nome_item_analise");
      }

      else
      {
        $body = PanteonEscolarBaseModule::preencherBarraVazia($node);
      }

      if(($nivel_acesso == "GESTOR") || ($nivel_acesso == "ADMINISTRADOR") || ($nivel_acesso == "EDITOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }
    }

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead")
    {
      $nodeHead = XmlUtil::CreateChild($current, "blockhead", "");
      XmlUtil::AddAttribute($nodeHead, "perfil", strtolower($nivel_acesso));

      $msg = "Bem-Vindo, " . ucfirst($this->_context->authenticatedUser()) . " (" . $nivel_acesso . ").";
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

  public function MinhaPropostaDeAcaoDBXML($context, $opcao)
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