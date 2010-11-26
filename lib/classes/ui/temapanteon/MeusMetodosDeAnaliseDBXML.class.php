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

class MeusMetodosDeAnaliseDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
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
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Dica Métodos de Análise");
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Como analisar o Tema Panteon? No método de análise você define os elementos necessários para análise das situações-problema e dos pontos de vista dos sujeitos. Após criar o Método de Análise, você precisa definir os itens de análise.', '');

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    if($this->_opcao == "processPageField") {
      $nome_modulo = "meusmetodosdeanalise";
      if($this->_context->ContextValue("acao") == 'ppmsgs') $this->_context->redirectUrl($nome_modulo);

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $dbxml = new MetodoAnaliseDBXML($this->_context, "meusmetodosdeanalise", "Meus Métodos de Análise");

      if($this->_context->ContextValue("acao") == "createItemAnalise") {
        $this->_context->addCookie("id_metodo_analise", $this->_context->ContextValue("valueid"));
        $this->_context->redirectUrl("/criaritemdeanalise");

      }

      $permissao = array(true, false, true, false, true);

      if($this->_context->ContextValue("id_meus_metodos_de_analise_filtro") == 2) {
        $id_usuario = "";
        $permissao = array(true, false, false, false, false);
      }

      if($nivel_acesso == "ADMINISTRADOR") {
        $permissao = array(true, true, true, true, true);
        $id_usuario = "";
      }

      $aviso = new XmlInputLabelObjects("<p></p>");
      $aviso->addXmlnukeObject(new XmlnukeText('<div id="informeDiscreto">Antes de cadastrar um novo Método de Análise verifique se não existe um equivalente.</div><br/>'));
      $span1->addXmlnukeObject($aviso);

      $pagina = $dbxml->criarProcessPageFields($id_usuario, $permissao);

      if($pagina->getAllRecords()->Count() > 0) {
        if($this->_context->ContextValue("acao") == "") $span1->addXmlnukeObject($this->filtro());
        $span1->addXmlnukeObject($pagina);

      } else {

        if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1)) {
          if($this->_context->ContextValue("acao") == "") {
            $span1->addXmlnukeObject($this->filtro());
          }

          $span1->addXmlnukeObject($pagina);
        } else {

          if($nivel_acesso == "ANALISTA") {
            $msg_criar_tema_panteon = '<div id="meusPontosDeVistas">Você não possui permissão para criar uma Estrutura Social.</div>';
          } else {
            $msg_criar_tema_panteon = '<div id="meusPontosDeVistas">Nenhum Método de Análise encontrado, cadastre o primeiro agora.<br/><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clicando Aqui</a></div>';

          }

          $aviso->addXmlnukeObject(new XmlNukeText($msg_criar_tema_panteon));
          if($this->_context->ContextValue("acao") == "") $span1->addXmlnukeObject($this->filtro());

        }

      }


    }

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuCriarTemaPanteon(PanteonEscolarMenu::MetodoDeAnalise));

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

  public function filtro() {
    $span = new XmlnukeSpanCollection();
    $formPost = "module:panteonescolar.meusmetodosdeanalise";
    $form = new XmlFormCollection($this->_context, $formPost, "Meus Métodos de Análise");
    $form->addXmlnukeObject($this->filtroMeusMetodosDeAnalise());

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);


    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroMeusMetodosDeAnalise() {
    $listaMetodoAnalise = array(1 => "Meus Métodos", 2 => "Todos os Métodos");
    $id_meus_metodos_de_analise_filtro_selecionado = $this->_context->ContextValue("id_meus_metodos_de_analise_filtro");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_meus_metodos_de_analise_filtro", "Métodos de Análise", $listaMetodoAnalise, $id_meus_metodos_de_analise_filtro_selecionado);

    return $lista;

  }

  public function MeusMetodosDeAnaliseDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>