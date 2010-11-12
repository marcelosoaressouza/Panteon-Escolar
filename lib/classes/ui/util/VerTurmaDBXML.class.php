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

class VerTurmaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current) {
    $id_turma_ver = $this->_context->ContextValue("verturma");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver") {
      $db = new UsuarioXTurmaDB($this->_context);
      $it = $db->obterTodosOsUsuariosPorIDTurma($id_turma_ver);

      if(!$it->hasNext()) $span1->addXmlnukeObject(new XmlNukeText("Ainda não possui usuários"));

      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Membros da Turma:</div><br/>'));

      while($it->hasNext()) {
        $sr = $it->moveNext();
        $usuario = "<b>".$sr->getField("nome_completo_usuario")."</b><br/>";
        $span1->addXmlnukeObject(new XmlNukeText($usuario));
      }


    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerTurmaDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>
