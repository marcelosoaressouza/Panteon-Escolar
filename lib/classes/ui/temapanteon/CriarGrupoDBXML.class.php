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

class CriarGrupoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_grupo");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    if($id_tema_panteon == "")
    {
      $this->_context->redirectUrl("module:panteonescolar.criartemapanteon");
    }

    $id_usuario = $this->_context->authenticatedUserId();

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "processPageField")
    {
      $span = new XmlnukeSpanCollection();
      $nome_modulo = "criargrupo";

      if($this->_context->ContextValue("acao") == 'ppmsgs')
      {
        $this->_context->redirectUrl($nome_modulo);
      }

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));


      if($this->_context->ContextValue("acao") == "adicionarUsuarioAoGrupo")
      {
        $this->_context->addCookie("id_grupo_selecionado", $this->_context->ContextValue("valueid"));
        $this->_context->redirectUrl("module:panteonescolar.criarusuarioxgrupo");

      }

      $dbxml = new GrupoDBXML($this->_context, "criargrupo", "Criar Grupo Tema Panteon");
      $permissao = array(true, false, true, false);
      $pagina = $dbxml->criarProcessPageFields($id_tema_panteon, $permissao);

      if($pagina->getAllRecords()->Count() > 0)
      {
        if($this->_context->ContextValue("acao") == "")
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
          $span1->addXmlnukeObject($this->filtro());
          $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Pesquisa não retornou nenhum Grupo.<br/><a href="/criargrupo&acao=ppnew">Criar Grupo</a></div>'));

        }

      }


    }

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Grupo');
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Cada grupo pode elaborar o “Diagnóstico de Grupo” e “Proposta de Ação do Grupo”, após análise e discussão sobre o Tema Panteon. Os grupos podem ser formados por usuários de diferentes turmas, que podem pertencer a diferentes instituições. ', '');

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
    $formPost = "module:panteonescolar.CriarGrupo";
    $form = new XmlFormCollection($this->_context, $formPost, "Filtro");


    //$form->addXmlnukeObject($this->filtroConfigTurma());
    $form->addXmlnukeObject($this->filtroInstituicao());
    $form->addXmlnukeObject($this->filtroTurma());

    $form->addXmlnukeObject(new XmlInputHidden("Pesquisar", true));

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroTurma($EasyListType = EasyListType::SELECTLIST)
  {
    //Debug::PrintValue("teste");
    $db = new TurmaDB($this->_context);
    $it = $db->obterTodosAsTurmasPorIDInstituicao($this->_context->ContextValue("id_instituicao_filtro"));

    $listaTurma = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_turma", "nome_turma");
    $listaTurma[""] = "Todas as Turmas";

    $id_turma_filtro_selecionado = $this->_context->ContextValue("id_turma_filtro");

//   $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_turma_filtro", "Turma", $listaTurma, $id_turma_filtro_selecionado);
    if($EasyListType == RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX_REQUEST)
    {
      $lista = new RanderNetXmlEasyList(RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX_REQUEST, "id_turma_filtro", "Turma", $listaTurma, $id_turma_filtro_selecionado);
    }

    else
    {
      $lista = new RanderNetXmlEasyList(RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX, "id_turma_filtro", "Turma", $listaTurma, $id_turma_filtro_selecionado);
    }




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

  public function CriarGrupoDBXML($context, $opcao)
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