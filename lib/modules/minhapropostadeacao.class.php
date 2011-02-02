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

ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseModule.class.php");

class MinhaPropostaDeAcao extends PanteonEscolarBaseModule
{

  public function CreatePage()
  {
    $myWords = $this->WordCollection();

    $titulo = "Escolha a Forma de Proposta de Ação";
    $this->defaultXmlnukeDocument = PanteonEscolarBaseModule::definirTitulo($titulo, $this->_context->getCookie("nome_tema_panteon_definido"));

    $blockHead   = new XmlBlockCollection(NULL, BlockPosition::Right);
    $blockCenter = new XmlBlockCollection(NULL, BlockPosition::Center);
    $blockInfo   = new XmlBlockCollection(NULL, BlockPosition::Left);
    $blockMenu   = new XmlBlockCollection(NULL, BlockPosition::Menu);

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockHead);
    $blockHead->addXmlnukeObject(new MinhaPropostaDeAcaoDBXML($this->_context, "menuHead"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockInfo);
    $blockInfo->addXmlnukeObject(new MinhaPropostaDeAcaoDBXML($this->_context, "listarDireita"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockMenu);
    $blockMenu->addXmlnukeObject(new MinhaPropostaDeAcaoDBXML($this->_context, "menu"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockCenter);
    $blockCenter->addXmlnukeObject(new MinhaPropostaDeAcaoDBXML($this->_context, "processPageField"));

    return $this->defaultXmlnukeDocument;

  }

  public function __constructor() {}
  public function __destruct() { }
  public function useCache()
  {
    return false;
  }

  public function requiresAuthentication()
  {
    return true;
  }

  public function getAccessLevel()
  {
    return AccessLevel::OnlyRole;
  }

  public function getRole()
  {
    return array("ANALISTA", "MEDIADOR", "GESTOR", "ADMINISTRADOR");
  }

}

?>
