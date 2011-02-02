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

class VerUsuarioDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id_usuario_ver = $this->_context->ContextValue("verusuario");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {
      if($id_usuario_ver != "")
      {
        $db = new UsuarioDB($this->_context);
        $nome_completo_usuario = $db->obterPorId($id_usuario_ver)->getNomeCompletoUsuario();

        $span1->addXmlnukeObject(new XmlNukeText('<div style="color: #A92D1E; font-size: 18px; font-weight:900;">Nome Completo</div>'));
        $span1->addXmlnukeObject(new XmlNukeText('<div style="color: #000000; font-weight:900;">'.$nome_completo_usuario.'</div><br/>'));

        $span1->addXmlnukeObject(new XmlNukeText('<div id ="titulo_confirma">Apagar este Usuário?</div>'));
        $span1->addXmlnukeObject(new XmlNukeText('<div style="color: #A92D1E; font-size: 16px; font-weight:900;"><a href="/configusuario&acao=delete&id='.$id_usuario_ver.'">Clique aqui para Apagar</a></div>'));


      }

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerUsuarioDBXML($context, $opcao)
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
