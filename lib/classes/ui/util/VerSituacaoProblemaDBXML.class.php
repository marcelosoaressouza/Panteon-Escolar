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

class VerSitucaoProblemaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id_tema_panteon = $this->_context->ContextValue("vertemapanteon");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {
      if($id_tema_panteon != "")
      {
        $db = new SituacaoProblemaDB($this->_context);
        $itSituacao = $db->obterTodasAsSituacoesProblemasPorIDTemaPanteon($id_tema_panteon);

        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Situações Problemas:</div><br/>'));

        while($itSituacao->hasNext())
        {
          $sr = $itSituacao->moveNext();
          $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">'.$sr->getField("nome_situacao_problema").'</div>'));
          $span1->addXmlnukeObject(new XmlNukeText('<div id="textover">'.$sr->getField("descricao_situacao_problema").'</div><br/>'));

        }
      }

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerSitucaoProblemaDBXML($context, $opcao)
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
