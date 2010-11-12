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

class MeuDiagnosticoGeralDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {

    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    if($this->_context->ContextValue("Pesquisar") == true && $this->_context->ContextValue("id_usuario_filtro")!="")
      $id_usuario = $this->_context->ContextValue("id_usuario_filtro");
    else
      $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    if($id_tema_panteon == "")
      $this->_context->redirectUrl("/meustemaspanteon");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "processPageField") {

      $nome_modulo = "meudiagnosticogeral";
      if($this->_context->ContextValue("acao") == 'ppmsgs')
        $this->_context->redirectUrl($nome_modulo);

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $db = new UsuarioXTemaPanteonDB($this->_context);
      $id_usuario_x_tema_panteon = $db->obterIDPorIDUsuarioXIDTemaPanteon($id_usuario, $id_tema_panteon);

      $dbxml = new DiagnosticoGeralDBXML($this->_context, "meudiagnosticogeral", "Meu Diagnóstico Geral");

      // permissao - $newRec, $view, $edit, $delete
      $permissao = array(true, false, true, false);
      $pagina = $dbxml->criarProcessPageFields($id_usuario_x_tema_panteon, $permissao);

      if($pagina->getAllRecords()->Count() > 0) {
        if($this->_context->ContextValue("acao") == "")
          $span1->addXmlnukeObject($this->filtro());
        $span1->addXmlnukeObject($pagina);

      } else {

        if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1)) {
          $span1->addXmlnukeObject($pagina);
        } else {
          if($this->_context->ContextValue("acao") == "")
            $span1->addXmlnukeObject($this->filtro());
          if($this->_context->ContextValue("Pesquisar") == true)
            $msg = '<br/><br/><br/><br/><div id="meusPontosDeVistas"> Este usuário não possui nenhum Diagnóstico Geral. <br/> </div>';
          else
            $msg = '<div id="meusPontosDeVistas"> Nenhum Diagnóstico Geral encontrado. Registre o primeiro agora. <br/> <a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clicando Aqui</a></div>';

          if($id_grupo == 65535)
            $msg = "Você ainda não faz parte de um grupo";
          $span1->addXmlnukeObject(new XmlNukeText($msg));

        }

      }

    }

    if($this->_opcao == "listarDireita") {
      $db = new UsuarioXPontoDeVistaDB($this->_context);
      $itDB = $db->obterTodosOsPontoDeVistaPorIDTemaPanteonXIDUsuario($id_tema_panteon, $id_usuario, 2, 1);
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Últimos Coletados");

      if($itDB->Count() > 0)
        $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "", "texto_ponto_de_vista", "nome_sujeito", PanteonEscolarConsts::PontoDeVista_URL, "id_ponto_de_vista");
      else
        $body = PanteonEscolarBaseModule::preencherBarraVazia($node);
      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::Diagnostico));

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

    $formPost = "module:panteonescolar.meudiagnosticogeral";
    $form = new XmlFormCollection($this->_context, $formPost, "Meu Diagnóstico Geral");

    //$form->addXmlnukeObject($this->filtroDescricao());
    $form->addXmlnukeObject($this->filtroUsuario());
    $form->addXmlnukeObject(new XmlInputHidden("Pesquisar", true));
    //$form->addXmlnukeObject($this->filtroEstruturaSocial());

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroUsuario() {
    $db = new UsuarioXTemaPanteonDB($this->_context);
    $it = $db->obterTodosOsUsuariosColetaramTemaPanteonPorIDTemaPanteon($this->_context->getCookie("id_tema_panteon_definido"));
    $listaUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_usuario", "nome_completo_usuario");
    $listaUsuario[""] = "Meus Diagnósticos Gerais";

    $id_usuario_filtro_selecionado = $this->_context->ContextValue("id_usuario_filtro");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_usuario_filtro", "Usuário", $listaUsuario, $id_usuario_filtro_selecionado);


    return $lista;

  }

  public function MeuDiagnosticoGeralDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>
