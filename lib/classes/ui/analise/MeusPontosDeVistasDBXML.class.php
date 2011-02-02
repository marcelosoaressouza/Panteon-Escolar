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

class MeusPontosDeVistasDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    if($id_tema_panteon == "")
    {
      $this->_context->redirectUrl("/meustemaspanteon");
    }

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
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $dbxml = new UsuarioXPontoDeVistaDBXML($this->_context, "meuspontosdevistas", "Meus Pontos de Vistas");
      $coletado = $this->_context->ContextValue("coletados");

      if($this->_context->ContextValue("acao") == "delete")
      {
        $db = new UsuarioXPontoDeVistaDB($this->_context);
        $db->excluir($this->_context->ContextValue("id"));
        $this->_context->addCookie("mensagem_aviso", "Ponto de Vista removido");
        $this->_context->redirectUrl("/todospontodevistatemapanteon");

      }

      $permissao = array(false, false, false, false);
      $pagina = $dbxml->criarProcessPageFields($id_usuario, $id_tema_panteon, $permissao, $coletado);

//      Filtro a ser implementado no DBXML
//      $span1->addXmlnukeObject($this->filtro($id_tema_panteon));

      if($pagina->getAllRecords()->Count() > 0)
      {
        $span1->addXmlnukeObject($pagina);

      }

      else
      {
        if($coletado == 0)
        {
          $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Ponto de Vista Descartado.</div>'));
        }

        else
        {
          $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Ponto de Vista Coletado.</div>'));
        }
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

  public function filtro($id_tema_panteon)
  {
    $span = new XmlnukeSpanCollection();
    $formPost = "module:panteonescolar.meuspontosdevistas";
    $form = new XmlFormCollection($this->_context, $formPost, "Ver Ponto de Vista");

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Filtrar");

    $form->addXmlnukeObject($this->filtroItemAnalise($id_tema_panteon));
    $form->addXmlnukeObject($this->filtroSituacaoProblema($id_tema_panteon));
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

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_item_analise_filtro", "Item Análise", $listaItemAnalise);

    return $lista;

  }

  public function filtroSituacaoProblema($id_tema_panteon)
  {
    $situacaoproblema = new SituacaoProblemaDB($this->_context);
    $it = $situacaoproblema->obterTodasAsSituacoesProblemasPorIDTemaPanteon($id_tema_panteon);

    $listaSituacaoProblem = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_situacao_problema", "nome_situacao_problema");

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_situacao_problema_filtro", "Situação-Problema", $listaSituacaoProblem);

    return $lista;

  }

  public function MeusPontosDeVistasDBXML($context, $opcao)
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