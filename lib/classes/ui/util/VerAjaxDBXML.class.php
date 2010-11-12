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

class VerAjaxDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current) {
    $uf_estado = $this->_context->ContextValue("verestado");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver") {
      $db = new PerfilDB($this->_context);
      $it = $db->obterCidadesPorUFEstado($uf_estado);

//      $span1->addXmlnukeObject(new XmlNukeText('<div id="cidades">'));
      $span1->addXmlnukeObject(new XmlNukeText('<select name="id_cidade" id="id_cidade">'));

      while($it->hasNext()) {
        $sr = $it->moveNext();
        $span1->addXmlnukeObject(new XmlNukeText('<option value="'.$sr->getField("id_cidade").'">'.$sr->getField("nome").'</option>'));


      }
      $span1->addXmlnukeObject(new XmlNukeText('</select>'));
//      $span1->addXmlnukeObject(new XmlNukeText('</div>'));

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerAjaxDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>
