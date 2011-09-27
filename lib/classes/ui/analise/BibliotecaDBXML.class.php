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

class BibliotecaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 4;

  public function generateObject($current)
  {
    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");

      //$db = new TemaPanteonDB($this->_context);
      //$itDB = $db->obterTodosPublicados(5);

      $body = PanteonEscolarBaseModule::criarTitulo($node, "Biblioteca Tema Panteon");
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Na Biblioteca de Temas Panteon você pode visualizar os Temas Panteon existentes. Caso queira analisar, selecione o Tema Panteon. Ele ficará registrado no item “Meus Temas”.', '');

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    if($this->_opcao == "processPageField")
    {
      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      if($this->_context->ContextValue("acao") == "getTemaPanteon")
      {

        $idTemaPanteon = $this->_context->ContextValue("valueid");
        $idUsuarioLogado = $this->_context->authenticatedUserId();

        $dbTemaPanteon = new TemaPanteonDB($this->_context);
        $modelTemaPanteon = new TemaPanteonModel();
        $modelTemaPanteon = $dbTemaPanteon->obterPorId($idTemaPanteon);

        $dbUsuarioXTemaPanteon = new UsuarioXTemaPanteonDB($this->_context);
        $modelUsuarioXTemaPanteon = new UsuarioXTemaPanteonModel();

        $modelUsuarioXTemaPanteon->setIDTemaPanteon($idTemaPanteon);
        $modelUsuarioXTemaPanteon->setIDUsuario($idUsuarioLogado);
        $modelUsuarioXTemaPanteon->setIDPermissao(1); // Analisar apenas

        $count = $dbUsuarioXTemaPanteon->verDuplicado($modelUsuarioXTemaPanteon);

        if($count == 0)
        {
          $modelUsuarioXTemaPanteon = $dbUsuarioXTemaPanteon->cadastrar($modelUsuarioXTemaPanteon);
          $this->_context->addCookie("mensagem_aviso", "<b>Tema Panteon Coletado</b>");
          $this->_context->addCookie("id_tema_panteon_definido", $idTemaPanteon);
          $this->_context->addCookie("nome_tema_panteon_definido", $modelTemaPanteon->getNomeTemaPanteon());
          $this->_context->redirectUrl("module:panteonescolar.meutemapanteon");

        }

        else
        {
          $this->_context->addCookie("mensagem_aviso", "<b>Este Tema Panteon já foi coletado</b>");
          $this->_context->addCookie("id_tema_panteon_definido", $idTemaPanteon);
          $this->_context->addCookie("nome_tema_panteon_definido", $modelTemaPanteon->getNomeTemaPanteon());
          $this->_context->redirectUrl("module:panteonescolar.meutemapanteon");

        }

        $span1->addXmlnukeObject($container);

      }

      $dbxml = new TemaPanteonDBXML($this->_context, "biblioteca", "Biblioteca");

      // permissao - $newRec, $view, $edit, $delete
      $permissao = array(false, false, false, false);
      $pagina_biblioteca = $dbxml->criarProcessPageFields($permissao, 2, "", true, false, true, "", true);

      if($pagina_biblioteca->getAllRecords()->Count() > 0)
      {
        if($this->_context->ContextValue("acao") == "")
        {
          $span1->addXmlnukeObject($this->filtro($nivel_acesso));
        }

        $span1->addXmlnukeObject($pagina_biblioteca);
      }

      else
      {
        $aviso = new XmlInputLabelObjects("<p></p>");
        $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Tema Panteon encontrado.</div>'));
        $span1->addXmlnukeObject($aviso);

        if($this->_context->ContextValue("acao") == "")
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
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuBiblioteca(PanteonEscolarMenu::Biblioteca));

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
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'biblioteca'));
      XmlUtil::AddAttribute($node, "nome_usuario", $msg);
      XmlUtil::AddAttribute($node, "logout", "true");

    }

    //
    // Fim - menu head


    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function filtro($nivel_acesso)
  {

    $span = new XmlnukeSpanCollection();

    $script  = '<script type="text/javascript">';
    $script .= ' $("#formGeralLabelid_metodo_analise_filtro").hide(); $("#id_metodo_analise_filtro").hide();';
    $script .= ' $("#formGeralLabelid_estrutura_social_filtro").hide(); $("#id_estrutura_social_filtro").hide();';
    $script .= '</script>';

    $script .= '<script type="text/javascript">';
    $script .= '$("#filtro").click(function () {';
    $script .= '  if ( $("#formGeralLabelid_metodo_analise_filtro").is(":hidden") ) {';
    $script .= '    $("#formGeralLabelid_metodo_analise_filtro").show(); $("#id_metodo_analise_filtro").show();';
    $script .= '    $("#formGeralLabelid_estrutura_social_filtro").show(); $("#id_estrutura_social_filtro").show();';
    $script .= '    $("#filtro").html("Busca simples");';
    $script .= '  }';
    $script .= ' else {';
    $script .= '    $("#formGeralLabelid_metodo_analise_filtro").hide(); $("#id_metodo_analise_filtro").hide();';
    $script .= '    $("#formGeralLabelid_estrutura_social_filtro").hide(); $("#id_estrutura_social_filtro").hide();';
    $script .= '    $("#filtro").html("Busca avançada");';
    $script .=  '}';
    $script .= '});';
    $script .= '</script>';

    $url = new XmlInputLabelObjects("<p></p>");
    $url->addXmlnukeObject(new XmlNukeText('<a id="filtro" href="#">Busca avançada</a><br/>'));

    $span->addXmlnukeObject($url);
    $span->addXmlnukeObject(new XmlNukeText($script));

    $formPost = "module:panteonescolar.biblioteca";
    $form = new XmlFormCollection($this->_context, $formPost, "Bilbioteca Tema Panteon");

    $form->addXmlnukeObject($this->filtroDescricao());

    if($nivel_acesso =="ADMINISTRADOR")
    {
      $form->addXmlnukeObject($this->filtroInstituicao());
    }

    $form->addXmlnukeObject($this->filtroMetodoAnalise());
    $form->addXmlnukeObject($this->filtroEstruturaSocial());

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;

  }

  public function filtroMetodoAnalise()
  {
    $db = new MetodoAnaliseDB($this->_context);
    $it = $db->obterTodos();
    $listaMetodoAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_metodo_analise", "nome_metodo_analise");
    $listaMetodoAnalise[""] = "Todos os Métodos de Análise";

    $id_metodo_analise_filtro_selecionado = $this->_context->ContextValue("id_metodo_analise_filtro");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_metodo_analise_filtro", "Método Análise", $listaMetodoAnalise, $id_metodo_analise_filtro_selecionado);

    return $lista;

  }

  public function filtroEstruturaSocial()
  {
    $db = new EstruturaSocialDB($this->_context);
    $it = $db->obterTodos();
    $listaEstruturaSocial = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_estrutura_social", "nome_estrutura_social");
    $listaEstruturaSocial[""] = "Todas as Estruturas Sociais";

    $id_estrutura_social_filtro_selecionado = $this->_context->ContextValue("id_estrutura_social_filtro");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_estrutura_social_filtro", "Estrutura Social", $listaEstruturaSocial, $id_estrutura_social_filtro_selecionado);

    return $lista;

  }

  public function filtroDescricao()
  {
    return new XmlInputTextBox("Texto: ", "descricao_tema_panteon_filtro", NULL, 40);

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
//    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_instituicao_filtro", "Instituição", $listaInstituicao, $id_instituicao_filtro_selecionado);

    return $lista;
  }

  public function BibliotecaDBXML($context, $opcao)
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