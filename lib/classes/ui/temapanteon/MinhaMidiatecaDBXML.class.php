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

class MinhaMidiatecaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {
    $id_tema_panteon_midiateca = $this->_context->getCookie("id_tema_panteon_midiateca");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraVazia($node);

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    if($this->_opcao == "processPageField")
    {
      $nome_modulo = "minhamidiateca";

      if($this->_context->ContextValue("acao") == 'ppmsgs')
      {
        $this->_context->redirectUrl($nome_modulo);
      }

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $span = new XmlnukeSpanCollection();
      $aviso = new XmlInputLabelObjects("<p></p>");
      $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas"> <a href="/criarmidiatecatemapanteon">Clique aqui para Retornar a Midiateca do Tema Panteon </a></div>'));
      $span1->addXmlnukeObject($aviso);

      // $opcoes = array (1 => "Endereço Internet (Link)", 2 => "Arquivo (Audio, Imagem, Video e/ou Texto");
      // $lista = new XmlEasyList(EasyListType::RADIOBOX, "arquivo_ou_link", "Esta mídia é um", $opcoes);
      // $span1->addXmlnukeObject($lista);

      $db = new TemaPanteonDB($this->_context);
      $nome_tema_panteon = $db->obterPorId($id_tema_panteon_midiateca)->getNomeTemaPanteon();
      $titulo = "Midiateca Geral - Colete os Arquivos Desejados ou Crie um Novo";

      if($this->_context->ContextValue("acao") == "getMidiatecaTemaPanteon")
      {
        $idMidiateca = $this->_context->ContextValue("valueid");

        $dbTemaPanteonXMidiateca = new TemaPanteonXMidiatecaDB($this->_context);
        $modelTemaPanteonXMidiateca = new TemaPanteonXMidiatecaModel();
        $modelTemaPanteonXMidiateca->setIDMidiateca($idMidiateca);
        $modelTemaPanteonXMidiateca->setIDTemaPanteon($id_tema_panteon_midiateca);
        $count = $dbTemaPanteonXMidiateca->verDuplicado($modelTemaPanteonXMidiateca);

        if($count == 0)
        {
          $modelTemaPanteonXMidiateca = $dbTemaPanteonXMidiateca->cadastrar($modelTemaPanteonXMidiateca);
          $container->addXmlnukeObject(new XmlnukeText("Midia Anexado ao Tema: ".$nome_tema_panteon, true));

        }

        else
        {
          $container->addXmlnukeObject(new XmlnukeText("Midia já foi anexada ao Tema: ".$nome_tema_panteon, true));

        }

        $span1->addXmlnukeObject($container);

      }

      $db = new MidiatecaDB($this->_context);
      $db->obterTodosRelacionados();
      $dbxml = new MidiatecaDBXML($this->_context, "minhamidiateca", $titulo);

      if($this->_context->ContextValue("id_minhas_midiatecas_filtro") !== "")
      {

        $this->_context->removeCookie("id_minhas_midiatecas_cookie");
        $this->_context->addCookie("id_minhas_midiatecas_cookie", $this->_context->ContextValue("id_minhas_midiatecas_filtro"));

        $filtro_midiateca = $this->_context->ContextValue("id_minhas_midiatecas_filtro");

        if($filtro_midiateca == 2)
        {
          $permissao = array(false, false, false, false);
          $pagina = $dbxml->criarProcessPageFields($permissao);

        }

        else
        {
          $permissao = array(true, false, true, true);
          $pagina = $dbxml->criarProcessPageFields($permissao, $id_usuario);

        }

      }

      else
      {
        if($this->_context->getCookie("id_minhas_midiatecas_cookie") !== "")
        {

          $filtro_midiateca = $this->_context->getCookie("id_minhas_midiatecas_cookie");

          if($filtro_midiateca == 2)
          {
            $permissao = array(false, false, false, false);
            $pagina = $dbxml->criarProcessPageFields($permissao);

          }

          else
          {
            $permissao = array(true, false, true, true);
            $pagina = $dbxml->criarProcessPageFields($permissao, $id_usuario);

          }

        }

        else
        {
          $this->_context->removeCookie("id_minhas_midiatecas_cookie");
          $permissao = array(true, false, true, true);
          $pagina = $dbxml->criarProcessPageFields($permissao, $id_usuario);

        }
      }

      if($pagina->getAllRecords()->Count() > 0)
      {
        if($this->_context->ContextValue("acao") != "ppnew")
        {
          $span1->addXmlnukeObject($this->filtro());
        }

        $span1->addXmlnukeObject($pagina);

      }

      else
      {

        if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1))
        {
          if($this->_context->ContextValue("acao") != "ppnew")
          {
            $span1->addXmlnukeObject($this->filtro());
          }

          $span1->addXmlnukeObject($pagina);
        }

        else
        {
          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(new XmlNukeText('<br/><div id="meusPontosDeVistas">Nenhuma Mídia encontrada, cadastre o primeiro agora <a href="/minhamidiateca&acao=ppnew">Clicando Aqui</a></div>'));
          $span1->addXmlnukeObject($aviso);

          if($this->_context->ContextValue("acao") != "ppnew")
          {
            $span1->addXmlnukeObject($this->filtro());
          }
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

  public function filtro()
  {
    $span = new XmlnukeSpanCollection();

    $script  = '<script type="text/javascript">';
    $script .= ' $("#formGeralLabelid_minhas_midiatecas_filtro").hide(); $("#id_minhas_midiatecas_filtro").hide();';
    $script .= ' $("#formGeralLabelid_tipo_midia_filtro").hide(); $("#id_tipo_midia_filtro").hide();';
    $script .= '</script>';

    $script .= '<script type="text/javascript">';
    $script .= '$("#filtro").click(function () {';
    $script .= '  if ( $("#formGeralLabelid_tipo_midia_filtro").is(":hidden") ) {';
    $script .= '    $("#formGeralLabelid_minhas_midiatecas_filtro").show(); $("#id_minhas_midiatecas_filtro").show();';
    $script .= '    $("#formGeralLabelid_tipo_midia_filtro").show(); $("#id_tipo_midia_filtro").show();';
    $script .= '    $("#filtro").html("Busca simples");';
    $script .= '  }';
    $script .= ' else {';
    $script .= '    $("#formGeralLabelid_minhas_midiatecas_filtro").hide(); $("#id_minhas_midiatecas_filtro").hide();';
    $script .= '    $("#formGeralLabelid_tipo_midia_filtro").hide(); $("#id_tipo_midia_filtro").hide();';
    $script .= '    $("#filtro").html("Busca avançada");';
    $script .=  '}';
    $script .= '});';
    $script .= '</script>';

    $url = new XmlInputLabelObjects("<p></p>");
    $url->addXmlnukeObject(new XmlNukeText('<a id="filtro" href="#">Busca avançada</a><br/>'));

    $span->addXmlnukeObject($url);
    $span->addXmlnukeObject(new XmlNukeText($script));

    $formPost = "module:panteonescolar.minhamidiateca";
    $form = new XmlFormCollection($this->_context, $formPost, "Minha Midiateca");

    $form->addXmlnukeObject($this->filtroMinhasMidiatecas());
    $form->addXmlnukeObject($this->filtroTagMidiateca());
    $form->addXmlnukeObject($this->filtroTipoMidiateca());

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroMinhasMidiatecas()
  {
    $listaMidiatecas = array(1 => "Minhas Midiatecas", 2 => "Todos as Midiatecas");

    $id_minhas_midiatecas_filtro_selecionado = $this->_context->ContextValue("id_minhas_midiatecas_filtro");

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_minhas_midiatecas_filtro", "Midiatecas", $listaMidiatecas, $id_minhas_midiatecas_filtro_selecionado);

    return $lista;

  }

  public function filtroTipoMidiateca()
  {
    $db = new TipoMidiaDB($this->_context);
    $it = $db->obterTodos();
    $listaTipoMidia = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_tipo_midia", "nome_tipo_midia");
    $listaTipoMidia[''] = "Todas os Tipos de Mídia";

    $id_tipo_midia_filtro_selecionado = $this->_context->ContextValue("id_tipo_midia_filtro");

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_tipo_midia_filtro", "Tipo Midia", $listaTipoMidia, $id_tipo_midia_filtro_selecionado);

    return $lista;

  }

  public function filtroTagMidiateca()
  {
    return new XmlInputTextBox("Texto: ", "nome_tag_filtro", NULL, 32);

  }

  public function MinhaMidiatecaDBXML($context, $opcao)
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