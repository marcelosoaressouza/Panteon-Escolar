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

class MidiatecaTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 5;

  public function generateObject($current)
  {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita")
    {
      $db = new UsuarioXTemaPanteonDB($this->_context);
      $itDB = $db->obterTodosOsUsuariosPorIDTemaPanteon($id_tema_panteon, 5);

      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Pesquisadores");

      if($itDB->Count() > 0)
      {
        $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "nome_completo_usuario", "nome_instituicao", "");
        XmlUtil::AddAttribute($node, "link_info", '<a href="pesquisadores">Clique aqui para ver lista completa de Pesquisadores</a>');
      }

      else
      {
        $body = PanteonEscolarBaseModule::preencherBarraVazia($node);
      }

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    if($this->_opcao == "processPageField")
    {
      $db = new TemaPanteonXMidiatecaDB($this->_context);
      $dbxml = new MidiatecaDBXML($this->_context, "midiatecatemapanteon", "Midiateca do Tema Panteon");

      $qtd_midiateca = $db->obterTodosOsTemasPanteonPorIDMidiateca($id_tema_panteon)->Count();

      if($qtd_midiateca > 0)
      {
        $permissao = array(false, false, false, false);
        $pagina = $dbxml->criarProcessPageFieldsPorTemaPanteon($id_tema_panteon, $permissao);

        if($pagina->getAllRecords()->Count() > 0)
        {
          $sobre = '<div id="caixaOpcao3">Na midiateca você encontra diversos arquivos como vídeos, sites, textos, animações, imagens e áudios que podem complementar seu estudo sobre o Tema Panteon. Bom Estudo!</div>';
          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(new XmlNukeText($sobre));
          $span1->addXmlnukeObject($aviso);

          if($this->_context->ContextValue("acao") != "ppnew")
          {
            $span1->addXmlnukeObject($this->filtro());
          }

          $span1->addXmlnukeObject($pagina);

        }

        else
        {
          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma Mídia encontrada</div>'));
          $span1->addXmlnukeObject($aviso);

          if($this->_context->ContextValue("acao") != "ppnew")
          {
            $span1->addXmlnukeObject($this->filtro());
          }

        }

      }

      else
      {
        $aviso = new XmlInputLabelObjects("<p></p>");
        $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma Mídia encontrada</div>'));
        $span1->addXmlnukeObject($aviso);

        if($this->_context->ContextValue("acao") != "ppnew")
        {
          $span1->addXmlnukeObject($this->filtro());
        }

      }

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::Midiateca));
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

    $script  = '<script type="text/javascript">';
    $script .= ' $("#formGeralLabelid_tipo_midia_filtro").hide(); $("#id_tipo_midia_filtro").hide();';
    $script .= '</script>';

    $script .= '<script type="text/javascript">';
    $script .= '$("#filtro").click(function () {';
    $script .= '  if ( $("#formGeralLabelid_tipo_midia_filtro").is(":hidden") ) {';
    $script .= '    $("#formGeralLabelid_tipo_midia_filtro").show(); $("#id_tipo_midia_filtro").show();';
    $script .= '    $("#filtro").html("Busca simples");';
    $script .= '  }';
    $script .= ' else {';
    $script .= '    $("#formGeralLabelid_tipo_midia_filtro").hide(); $("#id_tipo_midia_filtro").hide();';
    $script .= '    $("#filtro").html("Busca avançada");';
    $script .=  '}';
    $script .= '});';
    $script .= '</script>';

    $url = new XmlInputLabelObjects("<p></p>");
    $url->addXmlnukeObject(new XmlNukeText('<a id="filtro" href="#">Busca avançada</a><br/>'));

    $span->addXmlnukeObject($url);
    $span->addXmlnukeObject(new XmlNukeText($script));

    $formPost = "module:panteonescolar.midiatecatemapanteon";
    $form = new XmlFormCollection($this->_context, $formPost, "Midiateca");
    $form->addXmlnukeObject($this->filtroTipoMidiateca());
    $form->addXmlnukeObject($this->filtroTagMidiateca());

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

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

  public function MidiatecaTemaPanteonDBXML($context, $opcao)
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