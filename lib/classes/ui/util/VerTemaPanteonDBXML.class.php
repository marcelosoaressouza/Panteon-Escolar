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

class VerTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current) {
    $id_tema_panteon_ver = $this->_context->ContextValue("vertemapanteon");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver") {
      $db = new TemaPanteonDB($this->_context);
      $model = new TemaPanteonModel();
      $model = $db->obterPorId($id_tema_panteon_ver);
      $texto = $model->getDescricaoTemaPanteon();
      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Provocação Tema Panteon:</div><br/>'));
      $span1->addXmlnukeObject(new XmlNukeText($texto));

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerTemaPanteonDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>
