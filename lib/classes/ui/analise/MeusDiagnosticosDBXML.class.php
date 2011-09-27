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

class MeusDiagnosticosDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    if($this->_context->ContextValue("Pesquisar") == true && $this->_context->ContextValue("id_usuario_filtro")!="")
    {
      $id_usuario = $this->_context->ContextValue("id_usuario_filtro");
    }

    else
    {
      $id_usuario = $this->_context->authenticatedUserId();
    }

    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    if($id_tema_panteon == "")
    {
      $this->_context->redirectUrl("module:panteonescolar.meustemaspanteon");
    }

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita")
    {
      $db = new UsuarioXPontoDeVistaDB($this->_context);
      $itDB = $db->obterTodosOsPontoDeVistaPorIDTemaPanteonXIDUsuario($id_tema_panteon, $id_usuario, 2, 1);
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Coletados");

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

    if($this->_opcao == "processPageField")
    {

      $nome_modulo = "panteonescolar.meusdiagnosticos";

      if($this->_context->ContextValue("acao") == 'ppmsgs')
      {
        $this->_context->redirectUrl("module:".$nome_modulo);
      }

      //Debug::PrintValue($this->_context->ContextValue("nome_item_analise"));
      if($this->_context->ContextValue("acao") == 'ppnew_confirm' && ($this->_context->ContextValue("id_item_analise")!="") && ($this->_context->ContextValue("id_situacao_problema")!="") && ($this->_context->ContextValue("texto_diagnostico_individual"))!="")
      {
        $container = PanteonEscolarBaseModule::caixaAviso($this->_context);
        $idItemAnalise = $this->_context->ContextValue("id_item_analise");
        $idSituacaoProblema = $this->_context->ContextValue("id_situacao_problema");
        $dbItemAnalise = new ItemAnaliseDB($this->_context);
        $nome_item_analise = $dbItemAnalise->obterPorId($idItemAnalise)->getNomeItemAnalise();
        $dbSituacaoProblema = new ItemAnaliseDB($this->_context);
        $nome_situacao_problema = $dbSituacaoProblema->obterPorId($idItemAnalise)->getNomeItemAnalise();
        $container->addXmlnukeObject(new XmlnukeText("Item Análise: " . $nome_item_analise . " Situação problema  " . $nome_situacao_problema . " já foram cadastrados", true));
        //$span1->addXmlnukeObject($container);



        $dbDiagnosticoIndividual = new DiagnosticoIndividualDB($this->_context);
        $modelDiagnosticoIndividual = new DiagnosticoIndividualModel();
        $modelDiagnosticoIndividual->setIDItemAnalise($idItemAnalise);
        $modelDiagnosticoIndividual->setIDSituacaoProblema($idSituacaoProblema);
        $count = $dbDiagnosticoIndividual->verDuplicado($modelDiagnosticoIndividual);
        //Debug::PrintValue($count);


      }


      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $db = new UsuarioXTemaPanteonDB($this->_context);
      $id_usuarioxtemapanteon = $db->obterIDPorIDUsuarioXIDTemaPanteon($id_usuario, $id_tema_panteon);

      $dbxml = new DiagnosticoIndividualDBXML($this->_context, "meusdiagnosticos", "Meus Diagnósticos Específicos");

      // permissao - $newRec, $view, $edit, $delete
      $permissao = array(true, false, true, false);
      //$pagina = $dbxml->filtro($id_tema_panteon);
      //$span1->addXmlnukeObject($dbxml->filtro($id_tema_panteon));

      //exit();
      $pagina = $dbxml->criarProcessPageFields($id_usuarioxtemapanteon, $permissao, $id_tema_panteon);

      //$pagina->forceCurrentAction("ppnew");
      if($count > 0)
      {
        $span1->addXmlnukeObject($container);
        $pagina->forceCurrentAction("ppnew");
      }

      if($this->_context->ContextValue("acao") == "")
      {
        $aviso = new XmlInputLabelObjects("<p></p>");
        $txt = '<div id="caixaOpcao3">Qual seu ponto de vista? Após estudar os pontos de vista dos sujeitos, você pode construir seu próprio ponto de vista. Você pode construir um ponto de vista geral sobre o tema, ou um ponto de vista específico sobre cada situação-problema. Você também pode criar um diagnóstico de grupo.</div>';
        $txt .= '<div id="caixaOpcoes">';
        $url_link = $this->_context->bindModuleUrl("panteonescolar.meusdiagnosticosgrupo");
        /*
        $txt .= '<div id="caixaOpcao1">Diagnóstico do Grupo<br/>
                    <a href="'.$url_link.'">Clique aqui.</a>
                </div>';
         *
         */
        $url_link = $this->_context->bindModuleUrl("panteonescolar.meudiagnosticogeral");
        $txt .= '<div id="caixaOpcao2">Meu Diagnóstico Geral<br/>
                <a href="'.$url_link.'">Clique aqui.</a>
                </div>';
        $txt .= '</div>';
        $aviso->addXmlnukeObject(new XmlNukeText($txt));

        $span1->addXmlnukeObject($aviso);
      }

      if($pagina->getAllRecords()->Count() > 0)
      {
        if($this->_context->ContextValue("acao") == "" && (($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR")))
        {
          $span1->addXmlnukeObject($this->filtro());
        }

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

          $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas"> Para Criar/Editar um Diagnóstico Específico.<br/>
                                   <a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clique aqui</a></div>'));
        }

      }

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::Diagnostico));

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

    $formPost = "module:panteonescolar.meusdiagnosticos";
    $form = new XmlFormCollection($this->_context, $formPost, "Meus Diagnósticos Específicos");

    //$form->addXmlnukeObject($this->filtroDescricao());
    $form->addXmlnukeObject($this->filtroInstituicao());
    $form->addXmlnukeObject($this->filtroTurma());
    $form->addXmlnukeObject($this->filtroUsuario());
    $form->addXmlnukeObject(new XmlInputHidden("Pesquisar", true));
    //$form->addXmlnukeObject($this->filtroEstruturaSocial());

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroUsuario()
  {

    $db = new UsuarioXTemaPanteonDB($this->_context);
    $it = $db->obterTodosOsUsuariosColetaramTemaPanteonPorIDTemaPanteon($this->_context->getCookie("id_tema_panteon_definido"));
    $listaUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_usuario", "nome_completo_usuario");
    $listaUsuario[""] = "Todos os Usuários";

    $id_usuario_filtro_selecionado = $this->_context->ContextValue("id_usuario_filtro");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_usuario_filtro", "Usuário", $listaUsuario, $id_usuario_filtro_selecionado);


    return $lista;

  }

//  public function filtro() {
//    $span = new XmlnukeSpanCollection();
//    $formPost = "module:panteonescolar.minhasmensagens";
//    $form = new XmlFormCollection($this->_context, $formPost, "Minhas Mensagens");
//    $form->addXmlnukeObject($this->filtroInstituicao());
//    $form->addXmlnukeObject($this->filtroTurma());
//    $form->addXmlnukeObject($this->filtroMinhasMensagens());
//
//    $buttons = new XmlInputButtons();
//    $buttons->addSubmit("Pesquisar");
//    $form->addXmlnukeObject($buttons);
//
//    $span->addXmlnukeObject($form);
//
//    return $span;
//
//  }

  public function filtroTurma($EasyListType = EasyListType::SELECTLIST)
  {
    //Debug::PrintValue("teste");
    $db = new TurmaDB($this->_context);
    $it = $db->obterTodosAsTurmasPorIDInstituicao($this->_context->ContextValue("id_instituicao_filtro"));

    $listaTurma = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_turma", "nome_turma");
    $listaTurma[""] = "Todas as Turmas";

    $id_turma_filtro_selecionado = $this->_context->ContextValue("id_turma_filtro");

    if($EasyListType == RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX_REQUEST)
    {
      $lista = new RanderNetXmlEasyList(RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX_REQUEST, "id_turma_filtro", "Turma", $listaTurma, $id_turma_filtro_selecionado);
    }

    else
    {
      $lista = new RanderNetXmlEasyList(RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX, "id_turma_filtro", "Turma", $listaTurma, $id_turma_filtro_selecionado);
    }

//        $lista = new RanderNetXmlEasyList(EasyListType::SELECTLIST, "id_turma_filtro", "Turma", $listaTurma, $id_turma_filtro_selecionado);
//        $lista->setRanderNetDadosAjax("panteonescolar.configusuario", "id_usuario_filtro", "&amp;acao=usuario");

    return $lista;
  }

  public function filtroInstituicao()
  {
    //    Debug::PrintValue($this->_context->getXsl());
    $db = new InstituicaoDB($this->_context);
    $it = $db->obterTodos();

    $listaInstituicao = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_instituicao", "nome_instituicao");
    $listaInstituicao[""] = "Todas as Instituições";

    $id_instituicao_filtro_selecionado = $this->_context->ContextValue("id_instituicao_filtro");

    $lista = new RanderNetXmlEasyList(EasyListType::SELECTLIST, "id_instituicao_filtro", "Instituição", $listaInstituicao, $id_instituicao_filtro_selecionado);
    $lista->setRanderNetDadosAjax("panteonescolar.configusuario", "id_turma_filtro", "&amp;acao=turma");

    return $lista;
  }



  public function MeusDiagnosticosDBXML($context, $opcao)
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