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

class ErroDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current)
  {

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    $titulo = "Erro(s)";
    $nome_modulo = "Erro";

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraVazia($node);

    }

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead")
    {

      if($this->_context->IsAuthenticated())
      {
        $id_usuario = $this->_context->authenticatedUserId();
        $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);
        $nodeHead = XmlUtil::CreateChild($current, "blockhead", "");
        XmlUtil::AddAttribute($nodeHead, "perfil", strtolower($nivel_acesso));
        $msg = "Bem-Vindo, ".ucfirst($this->_context->authenticatedUser())." (".$nivel_acesso.").";

        $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
        $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, ''));
        XmlUtil::AddAttribute($node, "nome_usuario", $msg);
        XmlUtil::AddAttribute($node, "logout", "true");

      }

      else
      {
        $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
        $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadInicial());
        $body = PanteonEscolarBaseModule::preencherMenuHeadAuxiliar($node, PanteonEscolarBaseModule::preencherMenuHeadInicialAcesso('/xmlnuke.php?module=Erro&amp;action=action.NEWUSER&amp;ReturnUrl=%2fcriartemapanteon&amp;site=PanteonEscolar&amp;xsl=page&amp;xml=home&amp;lang=pt-br'));
      }

    }

    //
    // Fim - menu head

    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function ErroDBXML($context, $opcao)
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