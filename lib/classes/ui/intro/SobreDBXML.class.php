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

class SobreDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 4;

  public function generateObject($current)
  {

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "listarDireita")
    {
      $txt = " ";
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, "", "Não há Informações ", "");

    }

    if($this->_opcao == "processPageField")
    {
      $txt  = '<div id="titulo">Produzido por</div>';
      $txt .= '<div id="texto">Grupo de Trabalho de Produção de Conteúdos Digitais Educacionais da Secretaria da Educação do Estado da Bahia</div><br/>';
      $txt .= '<div id="titulo">Coordenação Geral</div>';
      $txt .= '<div id="texto">Alfredo Eurico Rodrigues Matta</div><br/>';
      $txt .= '<div id="titulo">Coordenação de Softwares Educacionais</div>';
      $txt .= '<div id="texto">Yuri Bastos Wanderley</div><br/>';
      $txt .= '<div id="titulo">Coordenação de Física</div>';
      $txt .= '<div id="texto">Paulo Augusto Oliveira Ramos</div><br/>';
      $txt .= '<div id="titulo">Coordenação Pedagógica</div>';
      $txt .= '<div id="texto">Ana Verena Carvalho</div><br/>';
      $txt .= '<div id="titulo">Coordenação de Audiovisual</div>';
      $txt .= '<div id="texto">Nalini Vergasta de Vasconcelos</div><br/>';
      $txt .= '<div id="titulo">Coordenação de Experimentos Educacionais</div>';
      $txt .= '<div id="texto">Sueli da Silva Xavier Cabalero</div><br/>';
      $txt .= '<div id="titulo">Analista de Sistema</div>';
      $txt .= '<div id="texto">Marcelo Soares Souza</div><br/>';

      $txt .= '<div id="titulo">Desenvolvimento</div>';
      $txt .= '<div id="texto">Tarcísio Silva de Araújo</div><br/>';

      $txt .= '<div id="titulo">Webdesigner</div>';
      $txt .= '<div id="texto">Aldo Gustavo Ribeiro</div><br/>';

      $txt .= '<div id="titulo">Voz e Dublagem</div>';
      $txt .= '<div id="texto">Maria Gabriela Rodrigues Piñeiro</div><br/>';


      $txt .= '<div id="titulo">Equipe Pedagógica</div>';
      $txt .= '<div id="texto">Isabele Ferreira Sodré</div>';
      $txt .= '<div id="texto">Patrícia Nascimento Pinto</div><br/>';

      $txt .= '<div id="titulo">Equipe de Física</div>';
      $txt .= '<div id="texto">Eduardo Menezes de Souza Amarante</div>';
      $txt .= '<div id="texto">José Mário Roullet</div>';
      $txt .= '<div id="texto">Paulo Henrique Lopes Pessoa</div><br/>';

      $txt .= '<div id="titulo">Roteiristas</div>';
      $txt .= '<div id="texto">Filipe Tiago Lima Pereira</div><br/>';

      $txt .= '<div id="titulo">Bolsistas de Iniciação Científica</div>';
      $txt .= '<div id="texto">Thiago Novais Rodrigues</div><br/>';

      $txt .= '<div id="titulo">Concepção e Autoria do Projeto</div>';
      $txt .= '<div id="texto">Alfredo Eurico Rodrigues Matta, Ana Verena Carvalho, Nalini Vergasta de Vasconcelos, Paulo Augusto Oliveira Ramos, Pollyana Fernandes, Sueli da Silva Xavier Cabalero, Vânia Rita de Menezes Valente, Yuri Bastos Wanderley</div>';

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
        $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'sobre'));
        XmlUtil::AddAttribute($node, "nome_usuario", $msg);
        XmlUtil::AddAttribute($node, "logout", "true");

      }

      else
      {
        $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadInicial('sobre'));
        $body = PanteonEscolarBaseModule::preencherMenuHeadAuxiliar($node, PanteonEscolarBaseModule::preencherMenuHeadInicialAcesso());

      }

    }

    //
    // Fim - menu head


    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function SobreDBXML($context, $opcao)
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