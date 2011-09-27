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

class VerVideoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_tipo_midia;
  protected $_arquivo;

  public function generateObject($current)
  {
    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $this->_tipo_midia = $this->_context->ContextValue("tipo");
    $this->_arquivo = $this->_context->ContextValue("arq");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    // Inicio Opções
    if($this->_opcao == "ver")
    {
      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Videoteca Tema Panteon</div><br/>'));

      if($this->_tipo_midia == "Video")
      {
        $span1->addXmlnukeObject($this->verVideo("/panteonescolar-src/www/"));
      }

    }

    // Fim Opções

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraVazia($node);

    }

    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario());
    }

    //
    // Fim - menu

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead")
    {
      $msg = "Bem-Vindo, ".ucfirst($this->_context->authenticatedUser())." (".$nivel_acesso.").";
      $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso));
      XmlUtil::AddAttribute($node, "nome_usuario", $msg);

    }

    //
    // Fim - menu head
    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function verVideo($url)
  {
    if($this->_arquivo != "")
    {

      $arquivo = "/upload/midiateca/".$this->_arquivo;
      $caminho = $url.$arquivo;
      $player = $url."/static/swf/Player.swf";
      $flashvars = 'file='.$caminho;

      $video  = '<center><object id="flashvideo" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="450" height="337" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab">';
      $video .= '<param name="movie" value="'.$player.'" />';
      $video .= '<param name="quality" value="high" />';
      $video .= '<param name="wmode" value="window" />';
      $video .= '<param name="allowfullscreen" value="true" />';
      $video .= '<embed src="'.$player.'" width="450" height="337" allowscriptaccess="always" allowfullscreen="true" id="player1" name="player1" flashvars="'.$flashvars.'"/>';
      $video .= '</object></center>';

    }

    return new XmlNukeText($video);

  }
  public function VerVideoDBXML($context, $opcao)
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
