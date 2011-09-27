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

class MeuTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $span2 = new XmlnukeSpanCollection();

    $this->addXmlnukeObject($span1);
    $this->addXmlnukeObject($span2);

    if($this->_opcao == "listarDireita")
    {

      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, "Pesquisadores");

      if($id_tema_panteon != "")
      {
        $db = new UsuarioXTemaPanteonDB($this->_context);
        $itDB = $db->obterTodosOsUsuariosPorIDTemaPanteon($id_tema_panteon, 5);

        if($itDB->Count() > 0)
        {
          $url_barra = $this->_context->bindModuleUrl("panteonescolar.minhasmensagens", "ver") . "&amp;&amp;chamada=1&amp;xsl=ver&amp;acao=ppnew&amp;usuario=";
          $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "nome_completo_usuario", "nome_instituicao", "nome_completo_usuario", $url_barra, "id_usuario", "Mensagem", "Envie mensagem para ");
          //$body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "nome_completo_usuario", "nome_instituicao", "", $url_barra, "id_usuario", "", "Envie mensagem para ");
        }

        else
        {
          $body = PanteonEscolarBaseModule::preencherBarraVazia($node);
        }


        $url_link = $this->_context->ContextValue("xmlnuke.URLMODULE") . "?module=panteonescolar.pesquisadores";
        XmlUtil::AddAttribute($node, "link_info", '<a href="'.$url_link.'">Clique aqui para ver lista completa de Pesquisadores</a>');

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

      if($id_tema_panteon != "")
      {
        $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario());
      }

      else
      {
        $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuTemaPanteon(PanteonEscolarMenu::TemaPanteon));
      }

    }

    //
    // Fim - menu

    // Inicio - Mostrar Meu Tema Panteon
    //
    if(($this->_opcao == "mostrarMeuTemaPanteon") && ($this->_context->ContextValue("action") == ""))
    {
      if($id_tema_panteon == "")
      {
        $url_link = $this->_context->bindModuleUrl("panteonescolar.meustemaspanteon");
        $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Tema Panteon escolhido.<br/> <a href="'.$url_link.'">Escolha um Tema Panteon para trabalhar.</a></div>'));

      }

      else
      {


        $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

        $db = new TemaPanteonDB($this->_context);
        $dbMA = new MetodoAnaliseDB($this->_context);
        $dbES = new EstruturaSocialDB($this->_context);
        $dbUsuario = new UsuarioDB($this->_context);

        $model = new TemaPanteonModel();
        $model = $db->obterPorId($id_tema_panteon);

        $sobre = '<div id="caixaOpcao3">Aqui começa a análise do Tema Panteon. Leia todas as informações abaixo, verifique o método de análise, a estrutura social, sujeitos e situações-problema. Após estudar todos esses elementos fundamentais para análise do tema, pesquise os pontos de vista dos sujeitos. Ao terminar a análise, você pode construir seu ponto de vista sobre o tema em Diagnóstico e elaborar uma Proposta de Ação. Bom Trabalho!</div>';

        $titulo_tema_panteon = '<div id="titulo_tema_panteon">'.$model->getNomeTemaPanteon().'</div>';
        $texto_tema_panteon = '<div id="subtitulos">Provocação:</div>'.$model->getDescricaoTemaPanteon().'<br/>';

        $url_link = $this->_context->bindModuleUrl("panteonescolar.vermetodoanalise");
        $linkMA = $url_link.'&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vermetodoanalise='.$model->getIDMetodoAnalise();
        $urlMA = '<b><a class="lista_direita_detalhe" href="'.$linkMA.'">'.$dbMA->obterPorId($model->getIDMetodoAnalise())->getNomeMetodoAnalise().' (Clique aqui e Saiba Mais)</a></b>';

        $url_link = $this->_context->bindModuleUrl("panteonescolar.verestruturasocialcomsujeitos");
        $linkES = $url_link.'&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;verestruturasocial='.$model->getIDEstruturaSocial();
        $urlES = '<b><a class="lista_direita_detalhe" href="'.$linkES.'">'.$dbES->obterPorId($model->getIDEstruturaSocial())->getNomeEstruturaSocial().' (Clique aqui e Saiba Mais)</a></b>';

        $textoMA = '<div id="subtitulos">Metódo de Análise:</div> <div id="link-titulo">'.$urlMA.'</div><br/>';
        $textoES = '<div id="subtitulos">Estrutura Social:</div>  <div id="link-titulo">'.$urlES.'</div><br/>';

        $url_link = $this->_context->bindModuleUrl("panteonescolar.versujeito");
        $url_sujeito = '<a class="lista_direita_detalhe" href="'.$url_link.'&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vertemapanteon='.$id_tema_panteon.'"> Clique Aqui para Listar Todos os Sujeitos deste Tema Panteon</a>';
        $sujeito = '<div id="subtitulos">Sujeito(s):</div>  <div id="link-titulo">'.$url_sujeito.'</div><br/>';

        $url_link = $this->_context->bindModuleUrl("panteonescolar.versituacaoproblema");
        $urlSP = '<a class="lista_direita_detalhe" href="'.$url_link.'&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vertemapanteon='.$id_tema_panteon.'"> Clique Aqui para ver todas as Situações Problema</a>';
        $textoSP = '<div id="subtitulos">Situações Problemas:</div>  <div id="link-titulo">'.$urlSP.'</div><br/>';

        //$url_barra = $this->_context->bindModuleUrl("panteonescolar.minhasmensagens", "ver") . "&amp;&amp;chamada=1&amp;xsl=ver&amp;acao=ppnew&amp;usuario=";
        //$body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "nome_completo_usuario", "nome_instituicao", "nome_completo_usuario", $url_barra, "id_usuario", "Mensagem", "Envie mensagem para ");
        //Debug::PrintValue($model->getIDUsuario());
        $autor = '<div id="subtitulos">Criador:</div>  <div id="link-titulo"><a class="lista_direita_detalhe" href="xmlnuke.php?module=panteonescolar.verperfil&site=PanteonEscolar&xsl=page&lang=pt-br&site=PanteonEscolar&xsl=ver&lang=pt-br&verperfil='.$model->getIDUsuario().'">'.$dbUsuario->obterPorId($model->getIDUsuario())->getNomeCompletoUsuario().'</a></div>';

        $span1->addXmlnukeObject(new XmlNukeText($sobre));
        $span1->addXmlnukeObject(new XmlNukeText($titulo_tema_panteon));
        $span2->addXmlnukeObject(new XmlNukeText($texto_tema_panteon));
        $span2->addXmlnukeObject(new XmlNukeText($textoMA));
        $span2->addXmlnukeObject(new XmlNukeText($textoES));
        $span2->addXmlnukeObject(new XmlNukeText($sujeito));
        $span2->addXmlnukeObject(new XmlNukeText($textoSP));
        $span2->addXmlnukeObject(new XmlNukeText($autor));

      }

    }

    //
    // Fim - Mostrar Meu Tema Panteon

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

  public function MeuTemaPanteonDBXML($context, $opcao)
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