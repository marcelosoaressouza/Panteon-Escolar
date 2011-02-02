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

class VerPerfilDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id_perfil_ver = $this->_context->ContextValue("verperfil");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {
      $db = new PerfilDB($this->_context);
      $modelPerfil = $db->obterPorId($id_perfil_ver);

      $db = new PerfilDB($this->_context);
      $cidade = $db->obterCidadePorIDCidade($modelPerfil->getIDCidade());
      $texto_perfil = $modelPerfil->getTextoPerfil();
      $dt_nasc = DateUtil::ConvertDate($modelPerfil->getDataNascimentoPerfil(), DATEFORMAT::YMD, DATEFORMAT::DMY, "/", false);
      $idade = PanteonEscolarBaseModule::idade($modelPerfil->getDataNascimentoPerfil());

      $info_nasc = '<div id="subtitulos">Data Nascimento: </div><div id="textover">'.$dt_nasc.' ('.$idade.' anos)'.'</div>';

      if($modelPerfil->getCaminhoFotoPerfil() != "")
      {
        $span1->addXmlnukeObject(new XmlNukeText('<div id="caixa_info_perfil_foto">'));
        $span1->addXmlnukeObject(new XmlNukeText('<img alt="Foto" src="'.$modelPerfil->getCaminhoFotoPerfil().'"/>'));
        $span1->addXmlnukeObject(new XmlNukeText('</div>'));
      }


      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Cidade: </div><div id="textover">'.$cidade.'</div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Estado: </div><div id="textover">'.$modelPerfil->getUFEstado().'</div>'));
      $span1->addXmlnukeObject(new XmlNukeText($info_nasc));
      $span1->addXmlnukeObject(new XmlNukeText('<br/><div id="subtitulos">Perfil Resumido: </div><div id="textover">'.$texto_perfil.'</div>'));

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerPerfilDBXML($context, $opcao)
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
