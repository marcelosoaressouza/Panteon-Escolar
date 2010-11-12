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

class VerTagDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current) {
    $id_tag_ver = $this->_context->ContextValue("vertag");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver") {
      if($id_tag_ver != "") {
        $span1->addXmlnukeObject(new XmlNukeText('<div id ="titulo_confirma">Você tem certeza que deseja remover esta Tag?</div>'));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="texto_confirma"><a href="/configtag&acao=delete&id='.$id_tag_ver.'">Clique aqui para confirmar</a></div>'));


      }

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerTagDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>
