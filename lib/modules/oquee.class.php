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

class OQueE extends PanteonEscolarBaseModule
{

  public function CreatePage() {
    $myWords = $this->WordCollection();

    $this->defaultXmlnukeDocument = new XmlnukeDocument("O Que é o Panteon Escolar?", "O Que é o Panteon Escolar?");

    $blockHead   = new XmlBlockCollection(NULL, BlockPosition::Right);
    $blockCenter = new XmlBlockCollection(NULL, BlockPosition::Center);
    $blockInfo   = new XmlBlockCollection(NULL, BlockPosition::Left);
    $blockMenu   = new XmlBlockCollection(NULL, BlockPosition::Menu);

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockHead);
    $blockHead->addXmlnukeObject(new OQueEDBXML($this->_context, "menuHead"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockInfo);
    $blockInfo->addXmlnukeObject(new OQueEDBXML($this->_context, "listarDireita"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockCenter);
    $blockCenter->addXmlnukeObject(new OQueEDBXML($this->_context, "processPageField"));

    return $this->defaultXmlnukeDocument;

  }

  public function __constructor() {}
  public function __destruct() { }
  public function useCache() {
    return false;
  }

  public function requiresAuthentication() {
    return false;
  }

}

?>
