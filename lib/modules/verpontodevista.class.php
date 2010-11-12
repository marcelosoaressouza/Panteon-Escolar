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

class VerPontoDeVista extends PanteonEscolarBaseModule
{

  public function CreatePage() {
    $myWords = $this->WordCollection();

    $titulo = "Ver Ponto de Vista";
    $this->defaultXmlnukeDocument = PanteonEscolarBaseModule::definirTitulo($titulo, $this->_context->getCookie("nome_tema_panteon_definido"));

    $blockCenter = new XmlBlockCollection(NULL, BlockPosition::Center);
    $this->defaultXmlnukeDocument->addXmlnukeObject($blockCenter);
    $blockCenter->addXmlnukeObject(new VerPontoDeVistaDBXML($this->_context, "ver"));

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
