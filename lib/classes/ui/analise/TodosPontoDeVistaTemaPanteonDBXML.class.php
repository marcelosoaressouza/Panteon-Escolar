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

class TodosPontoDeVistaTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 2;

  public function generateObject($current)
  {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");
    $data_hora_agora = date("Y-m-d H:i:s");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "processPageField")
    {

      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      if($this->_context->ContextValue("acao") == "coletarPontoDeVista")
      {

        $idPontoDeVista = $this->_context->ContextValue("valueid");

        $dbPontoDeVista = new PontoDeVistaDB($this->_context);
        $modelPontoDeVista = new PontoDeVistaModel();
        $modelPontoDeVista = $dbPontoDeVista->obterPorId($idPontoDeVista);

        $dbUsuarioXPontoDeVista = new UsuarioXPontoDeVistaDB($this->_context);
        $modelUsuarioXPontoDeVista = new UsuarioXPontoDeVistaModel();

        $modelUsuarioXPontoDeVista->setIDPontoDeVista($idPontoDeVista);
        $modelUsuarioXPontoDeVista->setIDUsuario($id_usuario);
        $modelUsuarioXPontoDeVista->setIDTemaPanteon($id_tema_panteon);
        $modelUsuarioXPontoDeVista->setDataHoraUsuarioXPontoDeVista($data_hora_agora);

        if($this->_context->ContextValue("subacao") == 'descartar')
        {
          $msgacao = "<b>Ponto de vista descartado:</b> ";
          $modelUsuarioXPontoDeVista->setColetadoUsuarioXPontoDeVista(0);

        }

        else
        {
          $msgacao = "<b>Ponto de vista coletado:</b> ";
          $modelUsuarioXPontoDeVista->setColetadoUsuarioXPontoDeVista(1);

        }

        $count = $dbUsuarioXPontoDeVista->verDuplicado($modelUsuarioXPontoDeVista);

        if($count == 0)
        {
          $modelUsuarioXPontoDeVista = $dbUsuarioXPontoDeVista->cadastrar($modelUsuarioXPontoDeVista);
          $this->_context->addCookie("mensagem_aviso", $msgacao.$modelPontoDeVista->getTextoPontodeVista());
          $this->_context->redirectUrl("module:panteonescolar.todospontodevistatemapanteon");

        }

        else
        {
          $this->_context->addCookie("mensagem_aviso", "Este Ponto de Vista já foi coletado ou descartado");
          $this->_context->redirectUrl("module:panteonescolar.todospontodevistatemapanteon");

        }

      }

      $dbxml = new PontodeVistaDBXML($this->_context, "todospontodevistatemapanteon", "Pontos de Vista");

      $permissao = array(false, false, false, false);

      $coletado = true;

      if($this->_context->ContextValue("id_coletados_filtro") == 1)
      {
        $coletado = false;

      }

      else if($this->_context->ContextValue("id_coletados_filtro") == 2)
      {
        $coletado = true;

      }

      else if($this->_context->ContextValue("id_coletados_filtro") == "")
      {
        if($this->_context->getCookie("id_coletados_filtro_Cookie") == 1)
        {
          $coletado = false;
        }

        else if($this->_context->getCookie("id_coletados_filtro_Cookie") == 2)
        {
          $coletado = true;
        }

      }

      $db = new SujeitoDB($this->_context);
      $it = $db->obterTodosOsSujeitosPorIDTemaPanteon($id_tema_panteon);

      if($it->Count() <= 0)
      {
        $aviso = new XmlInputLabelObjects("<p></p>");
        $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Sujeito encontrado.</div>'));
        $span1->addXmlnukeObject($aviso);

      }

      else
      {
        $paginaPontoDeVista = $dbxml->criarProcessPageFields("", $permissao, $id_usuario, $coletado, $id_tema_panteon);

        if($this->_context->ContextValue("acao") == "minhaMidiateca")
        {
          //$this->_context->addCookie("id_tema_panteon_midiateca", $this->_context->ContextValue("valueid"));

          // echo $this->_context->getCookie("id_ponto_de_vista");
          //echo $this->_context->addCookie("id_ponto_de_vista", $this->_context->ContextValue("valueid"));
          //exit();
          $this->_context->redirectUrl("./minhaMidiateca");

        }

        if($paginaPontoDeVista->getAllRecords()->Count() > 0)
        {
          $sobre = '<div id="caixaOpcao3">Pesquise os pontos de vista dos sujeitos com relação ao tema. Cada sujeito fala seu ponto de vista sobre determinada situação-problema. As situações-problema são analisadas sob diversos itens. Sendo assim, você deve pesquisar o ponto de vista selecionando a situação-problema e o item de análise.</div>';
          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(new XmlNukeText($sobre));
          $span1->addXmlnukeObject($aviso);

          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(PanteonEscolarBaseModule::meusPontosDeVistas($id_usuario, $id_tema_panteon, $this->_context));
          $span1->addXmlnukeObject($aviso);


          $dbEstruturaSocial = new TemaPanteonDB($this->_context);
          $id_estrutura_social = $dbEstruturaSocial->obterPorId($id_tema_panteon)->getIDEstruturaSocial();
          $span1->addXmlnukeObject($this->filtro($id_tema_panteon, $id_estrutura_social));
          $span1->addXmlnukeObject($paginaPontoDeVista);

        }

        else
        {
          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Ponto de Vista encontrado</div>'));
          $span1->addXmlnukeObject($aviso);
          $span1->addXmlnukeObject($this->filtro($id_tema_panteon));

        }
      }

    }

    if($this->_opcao == "listarDireita")
    {
      $db = new UsuarioXPontoDeVistaDB($this->_context);
      $itDB = $db->obterTodosOsPontoDeVistaPorIDTemaPanteonXIDUsuario($id_tema_panteon, $id_usuario, 2, 1);
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Últimos Coletados");

      if($itDB->Count() > 0)
      {
        $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "", "texto_ponto_de_vista", "nome_sujeito", PanteonEscolarConsts::PontoDeVista_URL, "id_ponto_de_vista");
      }

      else
      {
        $body = PanteonEscolarBaseModule::preencherBarraVazia($node);
      }

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::PontoDeVista));

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

  public function filtro($id_tema_panteon, $id_estrutura_social)
  {
    $span = new XmlnukeSpanCollection();
    $script  = '<script type="text/javascript">';
    $script .= ' $("#formGeralLabelid_item_analise_filtro").hide(); $("#id_item_analise_filtro").hide();';
    $script .= ' $("#formGeralLabelid_situacao_problema_filtro").hide(); $("#id_situacao_problema_filtro").hide();';
    $script .= ' $("#formGeralLabelid_grupo_social_filtro").hide(); $("#id_grupo_social_filtro").hide();';
    $script .= ' $("#formGeralLabelid_sujeito_filtro").hide(); $("#id_sujeito_filtro").hide();';
    $script .= '</script>';

    $script .= '<script type="text/javascript">';
    $script .= '$("#filtro").click(function () {';
    $script .= '  if ( $("#formGeralLabelid_item_analise_filtro").is(":hidden") ) {';
    $script .= '    $("#formGeralLabelid_item_analise_filtro").show(); $("#id_item_analise_filtro").show();';
    $script .= '    $("#formGeralLabelid_situacao_problema_filtro").show(); $("#id_situacao_problema_filtro").show();';
    $script .= '    $("#formGeralLabelid_grupo_social_filtro").show(); $("#id_grupo_social_filtro").show();';
    $script .= '    $("#formGeralLabelid_sujeito_filtro").show(); $("#id_sujeito_filtro").show();';
    $script .= '    $("#filtro").html("Busca simples");';
    $script .= '  }';
    $script .= ' else {';
    $script .= '    $("#formGeralLabelid_item_analise_filtro").hide(); $("#id_item_analise_filtro").hide();';
    $script .= '    $("#formGeralLabelid_situacao_problema_filtro").hide(); $("#id_situacao_problema_filtro").hide();';
    $script .= '    $("#formGeralLabelid_grupo_social_filtro").hide(); $("#id_grupo_social_filtro").hide();';
    $script .= '    $("#formGeralLabelid_sujeito_filtro").hide(); $("#id_sujeito_filtro").hide();';
    $script .= '    $("#filtro").html("Busca avançada");';
    $script .=  '}';
    $script .= '});';
    $script .= '</script>';

    $url = new XmlInputLabelObjects("<p></p>");

    $txt_busca = '<a id="filtro" href="#">Busca avançada</a><br/>';
    $url->addXmlnukeObject(new XmlNukeText($txt_busca));
    $span->addXmlnukeObject($url);
    $span->addXmlnukeObject(new XmlNukeText($script));

    $formPost = "module:panteonescolar.todospontodevistatemapanteon";
    $form = new XmlFormCollection($this->_context, $formPost, "Ver Ponto de Vista");

    //$form->addXmlnukeObject($this->filtroColetado());
    $form->addXmlnukeObject($this->filtroPontoDeVista());
    $form->addXmlnukeObject($this->filtroItemAnalise($id_tema_panteon));
    $form->addXmlnukeObject($this->filtroSituacaoProblema($id_tema_panteon));
    $form->addXmlnukeObject($this->filtroGruposSociais($id_estrutura_social));
    $form->addXmlnukeObject($this->filtroSujeito($id_tema_panteon));

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroItemAnalise($id_tema_panteon)
  {
    $temapanteon = new TemaPanteonDB($this->_context);
    $itemanalise = new ItemAnaliseDB($this->_context);
    $modelTemaPanteon = new TemaPanteonModel();

    $modelTemaPanteon = $temapanteon->obterPorId($id_tema_panteon);

    $it = $itemanalise->obterTodosOsItensAnalisePorIDMetodoAnalise($modelTemaPanteon->getIDMetodoAnalise());

    $listaItemAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_item_analise", "nome_item_analise");

    $listaItemAnalise[""] = "Todos os Itens de Análise";

    $id_item_analise_filtro_selecionado = $this->_context->ContextValue("id_item_analise_filtro");

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_item_analise_filtro", "Item Análise", $listaItemAnalise, $id_item_analise_filtro_selecionado);

    return $lista;

  }

  public function filtroSituacaoProblema($id_tema_panteon)
  {
    $situacaoproblema = new SituacaoProblemaDB($this->_context);
    $it = $situacaoproblema->obterTodasAsSituacoesProblemasPorIDTemaPanteon($id_tema_panteon);

    $listaSituacaoProblem = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_situacao_problema", "nome_situacao_problema");
    $listaSituacaoProblem[""] = "Todas as Situações Problemas";

    $id_situacao_problema_filtro_selecionado = $this->_context->ContextValue("id_situacao_problema_filtro");

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_situacao_problema_filtro", "Situação-Problema", $listaSituacaoProblem, $id_situacao_problema_filtro_selecionado);

    return $lista;

  }

  public function filtroGruposSociais($id_estrutura_social)
  {
    $gruposocial = new GrupoSocialDB($this->_context);
    $it = $gruposocial->obterTodosOsGruposSociaisPorIDEstruturaSocial($id_estrutura_social);

    $listaGrupoSocial = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_grupo_social", "nome_grupo_social");
    $listaGrupoSocial[""] = "Todas os Grupos Sociais";

    $id_grupo_social_filtro_selecionado = $this->_context->ContextValue("id_grupo_social_filtro");

    $dbEstruturaSocial = new EstruturaSocialDB($this->_context);
    $nome_estrutura_social = $dbEstruturaSocial->obterPorId($id_estrutura_social)->getNomeEstruturaSocial();
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_grupo_social_filtro", $nome_estrutura_social, $listaGrupoSocial, $id_grupo_social_filtro_selecionado);

    return $lista;

  }

  public function filtroSujeito($id_tema_panteon)
  {
    $sujeito = new SujeitoDB($this->_context);
    $it = $sujeito->obterTodosOsSujeitosPorIDTemaPanteon($id_tema_panteon);

    $listaSujeito = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_sujeito", "nome_sujeito");
    $listaSujeito[""] = "Todas os Sujeitos";

    $id_sujeito_filtro_selecionado = $this->_context->ContextValue("id_sujeito_filtro");

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_sujeito_filtro", "Sujeito", $listaSujeito, $id_sujeito_filtro_selecionado);

    return $lista;

  }

  public function filtroColetado()
  {
    $listaOpcao = array(2 => 'Apenas não Coletadas ou Descartados', 1 => 'Todos');

    if($this->_context->ContextValue("id_coletados_filtro") != "")
    {
      $id_coletados_filtro_selecionado = $this->_context->ContextValue("id_coletados_filtro");
      $this->_context->addCookie("id_coletados_filtro_Cookie", $id_coletados_filtro_selecionado);

    }

    else
    {
      if($this->_context->getCookie("id_coletados_filtro_Cookie") != "")
      {
        $id_coletados_filtro_selecionado = $this->_context->getCookie("id_coletados_filtro_Cookie");
      }

    }

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_coletados_filtro", "Coletados:", $listaOpcao, $id_coletados_filtro_selecionado);

    return $lista;

  }

  public function filtroPontoDeVista()
  {
    return new XmlInputTextBox("Texto: ", "texto_ponto_de_vista_filtro", NULL, 40);

  }

  public function TodosPontoDeVistaTemaPanteonDBXML($context, $opcao)
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