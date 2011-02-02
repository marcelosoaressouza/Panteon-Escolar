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

class VerMetodoAnaliseDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id_metodo_analise_ver = $this->_context->ContextValue("vermetodoanalise");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {
      $db = new MetodoAnaliseDB($this->_context);

      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Metódo de Análise:</div><br/>'));

      $texto = '<div id="subtitulos">Descrição</div><br/>';
      $texto .= $db->obterPorId($id_metodo_analise_ver)->getDescricaoMetodoAnalise().'<br/>';
      $span1->addXmlnukeObject(new XmlNukeText($texto));

      $db = new ItemAnaliseDB($this->_context);
      $it = $db->obterTodosOsItensAnalisePorIDMetodoAnalise($id_metodo_analise_ver);

      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Itens de Análise</div><br/>'));

      while($it->hasNext())
      {
        $sr = $it->moveNext();
        $txt = '<div id="textover">'.$sr->getField("nome_item_analise").'</div>';
        $span1->addXmlnukeObject(new XmlNukeText($txt));

      }


    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerMetodoAnaliseDBXML($context, $opcao)
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
