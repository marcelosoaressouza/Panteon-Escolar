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

class VerMensagemDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id_mensagem_ver = $this->_context->ContextValue("vermensagem");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {
      $db = new MensagemUsuarioDB($this->_context);
      $modelMensagem = $db->obterPorId($id_mensagem_ver);
      $dbUsuario = new UsuarioDB($this->_context);
      $modelUsuario = $dbUsuario->obterPorId($modelMensagem->getIDUsuarioOrig());
      $remetente = $modelUsuario->getNomeCompletoUsuario();
      $dataHorarioEnvioMensagem = $modelMensagem->getDataHoraCadastroMensagemUsuario();
      $assuntoMensagem = $modelMensagem->getNomeMensagemUsuario();
      $mensagem = $modelMensagem->getTextoMensagemUsuario();
      //Debug::PrintValue($mensagem);
      //exit();




      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Remetente: </div><div id="textover">'.$remetente.'</div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Data e horário: </div><div id="textover">'.$dataHorarioEnvioMensagem.'</div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Assunto: </div><div id="textover">'.$assuntoMensagem.'</div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Mensagem: </div><div id="textover"><span align="justify">'.$mensagem.'</span></div>'));

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerMensagemDBXML($context, $opcao)
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
