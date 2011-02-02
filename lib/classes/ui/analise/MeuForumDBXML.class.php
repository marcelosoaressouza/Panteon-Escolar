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

class MeuForumDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {



    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $respostas = false;

    if($id_tema_panteon == "")
    {
      $this->_context->redirectUrl("/meustemaspanteon");
    }

    $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "processPageField")
    {
      $db = new ForumDB($this->_context);
      $id_forum = $db->obterForumPorIDTemaPanteon($id_tema_panteon);

      $dbxml = new MensagemForumDBXML($this->_context, "meuforum", "Forum");

      $permissao = array(true, false, false, false, false);

      if($this->_context->ContextValue("acao") == "visualizarMensagens")
      {
        //$this->_context->addCookie("id_tema_panteon_definido", $this->_context->ContextValue("valueid"));
        $this->_context->addCookie("id_mensagem_forum", $this->_context->ContextValue("valueid"));

        $this->_context->redirectUrl("./meuforummensagenstopico");

      }

      if($this->_context->ContextValue("acao") == "responderMensagem")
      {
        //$idMensagemOriginal = $id_mensagem_forum;//$this->_context->ContextValue("id_mensagem_forum");//$this->_context->ContextValue("valueid");
        $dbMensagemForum = new MensagemForumDB($this->_context);

        if($dbMensagemForum->obterPorId($this->_context->ContextValue("valueid"))->getIDMensagemResposta()=="")
        {
          $idMensagemOriginal = $this->_context->ContextValue("valueid");
        }

        else
        {
          $idMensagemOriginal = $this->_context->getCookie("id_mensagem_forum");
        }

        $dbMensagemForum = new MensagemForumDB($this->_context);
        $dbUsuario = new UsuarioDB($this->_context);

        $nomeMensagemOriginalForum = $dbMensagemForum->obterPorId($idMensagemOriginal)->getNomeMensagemForum();
        $idUsuarioMensagemOriginal = $dbMensagemForum->obterPorId($idMensagemOriginal)->getIDUsuario();
        $autorMensagemOriginalForum = $dbUsuario->obterPorId($idUsuarioMensagemOriginal)->getNomeCompletoUsuario();

        $texto .= "<div id='meusPontosDeVistas'> ";
        $texto .= " Tópico: <a>".$nomeMensagemOriginalForum."</a>";
        $texto .= " Autor: <a>".$autorMensagemOriginalForum."</a>";
        $texto .= "</div>";

        $aviso = new XmlInputLabelObjects("<p></p>");
        $aviso->addXmlnukeObject(new XmlNukeText($texto));
        $span1->addXmlnukeObject($aviso);

        $pagina = $dbxml->criarProcessPageFields($id_forum, $id_usuario, $permissao, $idMensagemOriginal, false, $id_usuario);
        $pagina->forceCurrentAction("ppnew");
        $span1->addXmlnukeObject($pagina);

      }

      else
      {

        // Filtro
        if($this->_context->ContextValue("id_meu_forum_filtro") == 2)
        {
          $permissao = array(true, true, true, true, true);
          $id_usuario_orig = $id_usuario;

        }

        else if($this->_context->ContextValue("id_meu_forum_filtro") == 3)
        {
          $respostas = true;
          $permissao = array(false, false, false, false, false);
          $id_usuario_orig = $id_usuario;

        }

        else
        {
          $id_usuario_orig = "";

        }

        $pagina = $dbxml->criarProcessPageFields($id_forum, $id_usuario_orig, $permissao, NULL, $respostas, $id_usuario);

        if($this->_context->ContextValue("acao") == "")
        {
          $span1->addXmlnukeObject($this->filtro());
        }

        if($pagina->getAllRecords()->Count() > 0)
        {
          $span1->addXmlnukeObject($pagina);

        }

        else
        {


          if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1))
          {
            $span1->addXmlnukeObject($pagina);
          }

          else
          {
            if($respostas == false)
            {
              $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum registro encontrado, cadastre o primeiro agora <a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clicando Aqui</a></div>'));
            }

            else
            {
              $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma resposta encontrada.</div>'));
            }
          }
        }

      }
    }

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Dica Fórum");
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Neste espaço você, seus colegas e seu(a) professor(a) podem interagir e discutir sobre o Tema Panteon. ', '');

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::Forum));

    }

    //
    // Fim - menu

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead")
    {
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

  public function filtro()
  {
    $span = new XmlnukeSpanCollection();
    $formPost = "module:panteonescolar.meuforum";
    $form = new XmlFormCollection($this->_context, $formPost, "Meu Forum");

    //$form->addXmlnukeObject($this->filtroMeuForum());

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroMeuForum()
  {
    $listaMeuForum = array(0 => "-- Selecione --", 1 => "Todos as Mensagens", 2 => "Minhas Mensagens", 3 => "Respostas as Minhas Mensagens");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_meu_forum_filtro", "Meu Forum", $listaMeuForum);

    return $lista;

  }

  public function MeuForumDBXML($context, $opcao)
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