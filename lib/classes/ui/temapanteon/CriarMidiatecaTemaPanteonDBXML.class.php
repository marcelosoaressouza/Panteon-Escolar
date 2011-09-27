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

class CriarMidiatecaTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 5;

  public function generateObject($current)
  {
    $id_tema_panteon_midiateca = $this->_context->getCookie("id_tema_panteon_midiateca");
    $id_usuario = $this->_context->authenticatedUserId();

    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Midiateca');
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Na midiateca você pode anexar diversos  arquivos como vídeos, sites, textos, animações, imagens e áudios que podem complementar o estudo sobre o Tema Panteon.', '');

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    if($this->_opcao == "processPageField")
    {
      $url_modulo = $this->_context->bindModuleUrl("panteonescolar.criarmidiatecatemapanteon");

      if($this->_context->ContextValue("acao") == 'ppmsgs')
      {
        $this->_context->redirectUrl($url_modulo);
      }

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $span = new XmlnukeSpanCollection();
      $aviso = new XmlInputLabelObjects("<p></p>");
      $url = $this->_context->bindModuleUrl("panteonescolar.minhamidiateca");
      $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas"> <a href="'.$url.'">Clique aqui para Anexar ou Adicionar Nova Midia </a></div>'));
      $span1->addXmlnukeObject($aviso);

      $db = new TemaPanteonDB($this->_context);
      $titulo = "Minha Midiateca ".$db->obterPorId($id_tema_panteon_midiateca)->getNomeTemaPanteon();

      $db = new TemaPanteonXMidiatecaDB($this->_context);
      $db->obterTodasAsMidiatecasPorIDTemaPanteon($id_tema_panteon_midiateca);
      $dbxml = new TemaPanteonXMidiatecaDBXML($this->_context, "criarmidiatecatemapanteon", $titulo);

      // permissao - $newRec, $view, $edit, $delete
      $permissao = array(false, false, false, false);
      $pagina = $dbxml->criarProcessPageFields($id_tema_panteon_midiateca, $permissao, false);

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
          $span1->addXmlnukeObject(new XmlNukeBreakLine());
          $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma Mídia Anexada.</div>'));

        }

      }


    }

    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuCriarTemaPanteon(PanteonEscolarMenu::CriarTema));

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

  public function CriarMidiatecaTemaPanteonDBXML($context, $opcao)
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