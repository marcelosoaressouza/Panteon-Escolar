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

  public function generateObject($current) {
    $id_usuario = $this->_context->authenticatedUserId();
    if($id_usuario != "") $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita") {
      $txt = "O Panteon Escolar é uma adaptação do Panteon Web para as escolas, sendo este construído anteriormente por Marcos Cerqueira Lima como objeto de pesquisa do doutorado no Programa de Pós- Graduação em Comunicação e Cultura Contemporâneas da Universidade Federal da Bahia.";
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', $txt, '');

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR"))
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    if($this->_opcao == "processPageField")  {
      $txt = 'O Panteon Escolar é uma adaptação do Panteon Web para as escolas, sendo este construído
             anteriormente por Marcos Cerqueira Lima como objeto de pesquisa do doutorado no Programa de
             Pós- Graduação em Comunicação e Cultura Contemporâneas da Universidade Federal da Bahia.
             <br/><br/>
             O Panteon Escolar foi desenvolvido com características de um ambiente de pesquisa em que é
             possível estudar temáticas escolares, elaborar diagnóstico e proposta de ação.
             Nas temáticas são identificadas situações-problemas que serão analisadas sob diversos itens,
             definidos no método de análise. A análise de cada situação-problema se dá através da pesquisa dos
             Pontos de Vista dos sujeitos envolvidos com a temática. Esses pontos de vista são coletados em
             diversas fontes como livros, revistas, jornais, vídeos, sites, áudio ou por meio de entrevistas. Cada
             sujeito envolvido com o tema oferece uma visão de acordo com a sua experiência e/ou
             conhecimento.
             <br/><br/>
             Deste modo, os entendimentos sobre as situações problemas são representadas de acordo com os
             Pontos de Vista de cada sujeito e com base nos diferentes Itens de Análise do método construído e
             escolhido.
             <br/><br/>
             O público alvo inicial desta ferramenta são professores e alunos de escolas de ensino médio pública
             do Governo do Estado da Bahia.<br/><br/>
             <b><center><a href="static/manuais/Manual_Analista_Final.pdf"> Para mais informações veja o Manual do Analista </a></center></b>';
      $txt .= '<br/><img alt="Imagem Central" src="static/images/imagem_central-baixo.jpg"></img>';

      $span1->addXmlnukeObject(new XmlNukeText($txt));

    }

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead") {

      $node = XmlUtil::CreateChild($current, "blockbarramenu", "");

      if($this->_context->IsAuthenticated()) {
        $id_usuario = $this->_context->authenticatedUserId();
        $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

        $nodeHead = XmlUtil::CreateChild($current, "blockhead", "");
        XmlUtil::AddAttribute($nodeHead, "perfil", strtolower($nivel_acesso));

        $msg = "Bem-Vindo, ".ucfirst($this->_context->authenticatedUser())." (".$nivel_acesso.").";
        $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'oquee'));
        XmlUtil::AddAttribute($node, "nome_usuario", $msg);
        XmlUtil::AddAttribute($node, "logout", "true");

      } else {
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

  public function OQueEDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>
