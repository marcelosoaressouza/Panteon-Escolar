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

class VerUsuarioXPontoDeVistaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id_usuarioxpontodevista_ver = $this->_context->ContextValue("verusuarioxpontodevista");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {
      if($id_usuarioxpontodevista_ver != "")
      {
        $url = '/meuspontosdevistas&acao=delete&id='.$id_usuarioxpontodevista_ver;
        $retorno = $this->_context->ContextValue("returnurl");

        $db = new UsuarioXPontoDeVistaDB($this->_context);
        $id_ponto_de_vista = $db->obterPorId($id_usuarioxpontodevista_ver)->getIDPontodeVista();
        $db = new PontoDeVistaDB($this->_context);
        //$ponto_de_vista = strip_tags(substr($db->obterPorId($id_ponto_de_vista)->getTextoPontodeVista(), 0, 40));

        $span1->addXmlnukeObject(new XmlNukeText('<div style="color: #A92D1E; font-size: 18px; font-weight:900;">Ponto de Vista</div>'));
        //$span1->addXmlnukeObject(new XmlNukeText('<div style="color: #000000; font-weight:900;">'.$ponto_de_vista.'...</div><br/>'));

        $span1->addXmlnukeObject(new XmlNukeText('<div id ="titulo_confirma">Remover este Ponto de Vista?</div>'));
        $span1->addXmlnukeObject(new XmlNukeText('<div style="color: #A92D1E; font-size: 21px; font-weight:900;"><a href="'.$url.'">(Sim)</a> ou <a href="'.$retorno.'" id="fecharfancy">(Não)</a></div>'));


      }

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerUsuarioXPontoDeVistaDBXML($context, $opcao)
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
