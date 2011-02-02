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

class VerSujeitoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id_sujeito_ver = $this->_context->ContextValue("versujeito");
    $id_tema_panteon = $this->_context->ContextValue("vertemapanteon");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {
      if($id_sujeito_ver != "")
      {
        $db = new SujeitoDB($this->_context);
        $nome_sujeito = $db->obterPorId($id_sujeito_ver)->getNomeSujeito();
        $desc_sujeito = $db->obterPorId($id_sujeito_ver)->getDescricaoSujeito();
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">'.$nome_sujeito.'</div><br/>'));
        $span1->addXmlnukeObject(new XmlNukeText($desc_sujeito));
      }

      if($id_tema_panteon != "")
      {
        $db = new SujeitoDB($this->_context);
        $itSujeito = $db->obterTodosOsSujeitosPorIDTemaPanteon($id_tema_panteon);

        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Sujeito(s):</div><br/>'));

        while($itSujeito->hasNext())
        {
          $sr = $itSujeito->moveNext();
          $dbGrupoSocial = new GrupoSocialDB($this->_context);
          $grupo_social = $dbGrupoSocial->obterPorId($sr->getField("id_grupo_social"))->getNomeGrupoSocial();

          $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos">'.$sr->getField("nome_sujeito").'</div>'));
          $span1->addXmlnukeObject(new XmlNukeText('<div id="textover">'.$grupo_social.'</div><br/>'));

        }
      }

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerSujeitoDBXML($context, $opcao)
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
