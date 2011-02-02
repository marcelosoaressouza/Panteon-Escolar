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

class CriarTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {
    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");

      $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Tema Panteon');
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Professor (a), aqui você pode criar o Tema Panteon a ser estudado. Inicialmente, verifique se existe algum método de análise e estrutura social já cadastrada. Caso não possua nenhum cadastro, você precisará cadastrá-los antes de criar o Tema Panteon. A criação do Tema Panteon começa com a definição do tema, provocação, método de análise e estrutura social. Na etapa inicial você também define se o Tema Panteon  pode ser publicado, ficando disponível na Biblioteca Tema Panteon, e se ficará privado para instituição, permitindo apenas usuários da instituição analisar o Tema  Panteon. Após esta etapa você poderá cadastrar as situações-problema, os sujeitos, os pontos de vista, anexar arquivos, criar grupos e mensagens no mural. Bom Trabalho!', '');

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    if($this->_opcao == "processPageField")
    {
      $nome_modulo = "criartemapanteon";

      if($this->_context->ContextValue("acao") == 'ppmsgs')
      {
        $this->_context->redirectUrl($nome_modulo);
      }

      // Mensagem de Avisos
      $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

      $id_instituicao = "";

      // permissao - $newRec, $view, $edit, $delete
      $permissao = array(true, false, true, false, true);
      $criar_midiateca = true;

      $dbxml = new TemaPanteonDBXML($this->_context, "criartemapanteon", "Criar Tema Panteon");

      if(($nivel_acesso == "GESTOR") || ($nivel_acesso == "ADMINISTRADOR"))
      {
        // Filtro
        if(($this->_context->ContextValue("id_temas_panteon_filtro") == 2) || ($this->_context->getCookie("id_temas_panteon_filtro_Cookie") == 2))
        {

          if($nivel_acesso == "EDITOR")
          {
            $permissao = array(false, false, false, false, false);
            $criar_midiateca = false;

          }

        }

        else
        {
          $db = new UsuarioDB($this->_context);
          $id_instituicao = $db->obterPorId($id_usuario)->getIDInstituicao();
        }

      }

      $admin = false;

      if($nivel_acesso == "ADMINISTRADOR")
      {
        $permissao = array(true, true, true, true, true);
        $admin = true;

      }

      $pagina = $dbxml->criarProcessPageFields($permissao, 3, $id_usuario, false, $criar_midiateca, false, $id_instituicao, false, $admin);

      if($this->_context->ContextValue("acao") == "criarMidiatecaTemaPanteon")
      {
        $this->_context->addCookie("id_tema_panteon_midiateca", $this->_context->ContextValue("valueid"));
        $this->_context->redirectUrl("/criarmidiatecatemapanteon");

      }

      else if($this->_context->ContextValue("acao") == "criarPontoDeVista")
      {
        $id_tema_panteon_ponto_de_vista = $this->_context->ContextValue("valueid");
        $db = new TemaPanteonDB($this->_context);
        $id_estrutura_social_ponto_de_vista = $db->obterPorId($id_tema_panteon_ponto_de_vista)->getIDEstruturaSocial();
        $this->_context->addCookie("id_tema_panteon_ponto_de_vista", $id_tema_panteon_ponto_de_vista);
        $this->_context->addCookie("id_estrutura_social_ponto_de_vista", $id_estrutura_social_ponto_de_vista);
        $this->_context->redirectUrl("/criarpontodevistatemapanteon");

      }

      else if($this->_context->ContextValue("acao") == "criarSituacaoProblema")
      {
        $id_tema_panteon_situacao_problema = $this->_context->ContextValue("valueid");
        $this->_context->addCookie("id_tema_panteon_situacao_problema", $id_tema_panteon_situacao_problema);
        $this->_context->redirectUrl("/criarsituacaoproblematemapanteon");

      }

      else if($this->_context->ContextValue("acao") == "criarMural")
      {
        $this->_context->addCookie("id_tema_panteon_mural", $this->_context->ContextValue("valueid"));
        $this->_context->redirectUrl("./criarmural");

      }

      else if($this->_context->ContextValue("acao") == "criarForum")
      {
        $db = new ForumDB($this->_context);
        $forum_model = new ForumModel();
        $forum_model->setIDTemaPanteon($this->_context->ContextValue("valueid"));

        if($db->verDuplicado($forum_model->getIDTemaPanteon()) > 0)
        {
          $container->addXmlnukeObject(new XmlNukeText("Forum já foi criado com Sucesso!"));
        }

        else
        {
          $db->cadastrar($forum_model);
          $container->addXmlnukeObject(new XmlNukeText("Forum criado com Sucesso!"));
        }

        $span1->addXmlnukeObject($container);

      }

      else if($this->_context->ContextValue("acao") == "criarGrupo")
      {
        $id_tema_panteon_grupo = $this->_context->ContextValue("valueid");
        $this->_context->addCookie("id_tema_panteon_grupo", $id_tema_panteon_grupo);
        $this->_context->redirectUrl("./criargrupo");

      }

      $aviso = new XmlInputLabelObjects("<p></p>");
      $aviso->addXmlnukeObject(new XmlNukeText('<div id="informeDiscreto">Antes de criar um novo Tema, verifique os Métodos de Análise e as Estruturas Sociais cadastradas</div><br/>'));
      $span1->addXmlnukeObject($aviso);

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
          $msg_criar_tema_panteon = 'Nenhum Tema Panteon encontrado, cadastre o primeiro agora <a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clicando Aqui</a>';

          if($nivel_acesso == "ANALISTA")
          {
            $msg_criar_tema_panteon = 'Você não possui permissão para criar um Tema Panteon.';
          }

          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">'.$msg_criar_tema_panteon.'</div>'));
          $span1->addXmlnukeObject($aviso);

          if($this->_context->ContextValue("acao") == "")
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
    $script .= ' $("#formGeralLabelid_metodo_analise_filtro").hide(); $("#id_metodo_analise_filtro").hide();';
    $script .= ' $("#formGeralLabelid_estrutura_social_filtro").hide(); $("#id_estrutura_social_filtro").hide();';
    $script .= ' $("#formGeralLabelid_temas_panteon_filtro").hide(); $("#id_temas_panteon_filtro").hide();';
    $script .= '</script>';

    $script .= '<script type="text/javascript">';
    $script .= '$("#filtro").click(function () {';
    $script .= '  if ( $("#formGeralLabelid_metodo_analise_filtro").is(":hidden") ) {';
    $script .= '    $("#formGeralLabelid_metodo_analise_filtro").show(); $("#id_metodo_analise_filtro").show();';
    $script .= '    $("#formGeralLabelid_estrutura_social_filtro").show(); $("#id_estrutura_social_filtro").show();';
    $script .= '    $("#formGeralLabelid_temas_panteon_filtro").show(); $("#id_temas_panteon_filtro").show();';
    $script .= '    $("#filtro").html("Busca simples");';
    $script .= '  }';
    $script .= ' else {';
    $script .= '    $("#formGeralLabelid_metodo_analise_filtro").hide(); $("#id_metodo_analise_filtro").hide();';
    $script .= '    $("#formGeralLabelid_estrutura_social_filtro").hide(); $("#id_estrutura_social_filtro").hide();';
    $script .= '    $("#formGeralLabelid_temas_panteon_filtro").hide(); $("#id_temas_panteon_filtro").hide();';
    $script .= '    $("#filtro").html("Busca avançada");';
    $script .=  '}';
    $script .= '});';
    $script .= '</script>';

    $url = new XmlInputLabelObjects("<p></p>");
    $url->addXmlnukeObject(new XmlNukeText('<a id="filtro" href="#">Busca avançada</a><br/>'));

    $span->addXmlnukeObject($url);
    $span->addXmlnukeObject(new XmlNukeText($script));

    $formPost = "module:panteonescolar.criartemapanteon";
    $form = new XmlFormCollection($this->_context, $formPost, "Criar Tema Panteon");

    $span->addXmlnukeObject($form);
    $form->addXmlnukeObject($this->filtroMinhasMensagens());
    $form->addXmlnukeObject($this->filtroDescricao());
    $form->addXmlnukeObject($this->filtroMetodoAnalise());
    $form->addXmlnukeObject($this->filtroEstruturaSocial());

    $buttons = new XmlInputButtons();
    $buttons->addSubmit("Pesquisar");
    $form->addXmlnukeObject($buttons);

    return $span;

  }

  public function filtroMinhasMensagens()
  {
    $listaTemaPanteon = array(2 => "Temas Panteon da Instituição", 1 => "Meus Temas Panteon");

    if($this->_context->ContextValue("id_temas_panteon_filtro") != "")
    {
      $id_temas_panteon_filtro_selecionado = $this->_context->ContextValue("id_temas_panteon_filtro");
      $this->_context->addCookie("id_temas_panteon_filtro_Cookie", $id_temas_panteon_filtro_selecionado);

    }

    else
    {
      if($this->_context->getCookie("id_temas_panteon_filtro_Cookie") != "")
      {
        $id_temas_panteon_filtro_selecionado = $this->_context->getCookie("id_temas_panteon_filtro_Cookie");
      }

    }

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_temas_panteon_filtro", "Temas Panteon", $listaTemaPanteon, $id_temas_panteon_filtro_selecionado);

    return $lista;

  }

  public function filtroMetodoAnalise()
  {
    $db = new MetodoAnaliseDB($this->_context);
    $it = $db->obterTodos();
    $listaMetodoAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_metodo_analise", "nome_metodo_analise");
    $listaMetodoAnalise[""] = "Todos os Metódos de Análise";

    $id_metodo_analise_filtro_selecionado = $this->_context->ContextValue("id_metodo_analise_filtro");

    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_metodo_analise_filtro", "Metódo Análise", $listaMetodoAnalise, $id_metodo_analise_filtro_selecionado);

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
    return new XmlInputTextBox("Texto ", "descricao_tema_panteon_filtro", NULL, 50);

  }

  public function CriarTemaPanteonDBXML($context, $opcao)
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
