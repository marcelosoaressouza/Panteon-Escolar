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

class OQueEDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 4;

  public function generateObject($current)
  {
    $id_usuario = $this->_context->authenticatedUserId();

    if($id_usuario != "")
    {
      $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);
    }

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita")
    {
      $txt = "O Panteon Escolar é um software livre que possibilita o estudo aprofundado de variadas temáticas a partir do acesso a diversas fontes de pesquisa, do confronto de diferentes pontos de vista e do compartilhamento de opiniões.";
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', $txt, '');

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }

    }

    if($this->_opcao == "processPageField")
    {
      $txt = 'O Panteon Escolar é um software livre que possibilita o estudo aprofundado de variadas
             temáticas a partir do acesso a diversas fontes de pesquisa, do confronto de diferentes
             pontos de vista e do compartilhamento de opiniões.
             <br/><br/>
             O estudo do Tema se dá por meio da análise dos Pontos de Vista de Sujeitos envolvidos com o
             Tema, de textos, músicas, imagens, vídeos e sites indicados na Midiateca; e da interação e
             discussão a respeito dos Temas nos Fóruns e Chats. O ambiente também disponibiliza espaço
             para elaboração de Diagnósticos e Propostas de Ação, individuais e coletivas, nas quais os(as)
             alunos(as) podem construir seus próprios Pontos de Vista e propor ações para solucionar os
             problemas identificados durante a análise do tema.
             <br/><br/>
             O ambiente permite o estudo de temas disponíveis na Biblioteca de Temas Panteon ou a criação
             de novos temas para análise.
             <br/><br/>
             Na criação do tema, atividades de pesquisa podem ser estimuladas. Os pontos de vista podem ser
             pesquisador em diversas fontes como entrevistas, livros, revistas, jornais, vídeos, sites,
             áudios, favorecendo ao confronto de opiniões e uma compreensão crítica do tema.
             <br/><br/>
             Todas as informações de uso do Panteon Escolar você encontra no
             <b><a href="static/manuais/Manual_Instrucoes.pdf">Manual de Instruções</a></b> e no
             <b><a href="static/manuais/Guia_Panteon.pdf">Guia Pedagógico</a></b>.
             <br/><br/><br/><br/>
             Bom Estudo!';
      $txt .= '<br/><img alt="Imagem Central" src="static/images/imagem_central-baixo.jpg"></img>';

      $span1->addXmlnukeObject(new XmlNukeText($txt));

    }

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead")
    {

      $node = XmlUtil::CreateChild($current, "blockbarramenu", "");

      if($this->_context->IsAuthenticated())
      {
        $id_usuario = $this->_context->authenticatedUserId();
        $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

        $nodeHead = XmlUtil::CreateChild($current, "blockhead", "");
        XmlUtil::AddAttribute($nodeHead, "perfil", strtolower($nivel_acesso));

        $msg = "Bem-Vindo, ".ucfirst($this->_context->authenticatedUser())." (".$nivel_acesso.").";
        $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'oquee'));
        XmlUtil::AddAttribute($node, "nome_usuario", $msg);
        XmlUtil::AddAttribute($node, "logout", "true");

      }

      else
      {
        $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadInicial('oquee'));
        $body = PanteonEscolarBaseModule::preencherMenuHeadAuxiliar($node, PanteonEscolarBaseModule::preencherMenuHeadInicialAcesso());

      }

    }

    //
    // Fim - menu head


    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function OQueEDBXML($context, $opcao)
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
