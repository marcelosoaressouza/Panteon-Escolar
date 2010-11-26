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

class CriarGrupoSocialDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {
    $id_estrutura_social = $this->_context->getCookie("id_estrutura_social");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita") {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Grupos Sociais');
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Cada sujeito tem o seu lugar na sociedade. Dessa forma, cada um faz parte de um grupo social. No cadastro do grupo social você pode definir como grupo pai, um grupo já cadastrado. A definição de um grupo pai é opcional. O que é grupo pai? O grupo pai é um grupo maior, no qual grupos menores fazem parte como, por exemplo, escola e universidade pertencem a um grupo maior, ou seja, ao grupo acadêmico. Dessa forma, neste exemplo, o grupo pai é o grupo acadêmico.', '');
      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR")) XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    if($this->_opcao == "processPageField") {
      $nome_modulo = "criargruposocial";
      if($this->_context->ContextValue("acao") == 'ppmsgs') $this->_context->redirectUrl($nome_modulo);

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $db = new EstruturaSocialDB($this->_context);
      $titulo = "Estrutura Social: ".$db->obterPorId($id_estrutura_social)->getNomeEstruturaSocial();

      $dbxml = new GrupoSocialDBXML($this->_context, "criargruposocial", $titulo);

      // permissao - $newRec, $view, $edit, $delete
      $permissao = array(true, false, true, false);
      $pagina = $dbxml->criarProcessPageFields($id_estrutura_social, $permissao);

      if($pagina->getAllRecords()->Count() > 0) {
        $span1->addXmlnukeObject($pagina);

      } else {

        if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1)) {
          $span1->addXmlnukeObject($pagina);
        } else {
          $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum grupo social encontrado, cadastre o primeiro agora. <br/><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clicando Aqui</a></div>'));
        }

      }


    }

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuCriarTemaPanteon(PanteonEscolarMenu::EstruturaSocial));

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

  public function CriarGrupoSocialDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>