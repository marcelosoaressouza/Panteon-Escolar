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

ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseModule.class.php");

class MinhaMidiateca extends PanteonEscolarBaseModule
{

  public function CreatePage()
  {
    $myWords = $this->WordCollection();

    $titulo = "Midiateca Geral";
    $this->defaultXmlnukeDocument = PanteonEscolarBaseModule::definirTituloSimples($titulo);

    $blockHead = new XmlBlockCollection(NULL, BlockPosition::Right);
    $blockCenter = new XmlBlockCollection(NULL, BlockPosition::Center);
    $blockInfo = new XmlBlockCollection(NULL, BlockPosition::Left);
    $blockMenu = new XmlBlockCollection(NULL, BlockPosition::Menu);

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockHead);
    $blockHead->addXmlnukeObject(new MinhaMidiatecaDBXML($this->_context, "menuHead"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockInfo);
    $blockInfo->addXmlnukeObject(new MinhaMidiatecaDBXML($this->_context, "listarDireita"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockMenu);
    $blockMenu->addXmlnukeObject(new MinhaMidiatecaDBXML($this->_context, "menu"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockCenter);
    $blockCenter->addXmlnukeObject(new MinhaMidiatecaDBXML($this->_context, "processPageField"));

    $jsSource = "$(document).ready(function() {
                $(function() { $('#url_midiateca').click(function() {
                $('#caminho_arquivo_midiateca').val('');
              });
              });

                $(function() {
                $('#caminho_arquivo_midiateca').click(function() {
                $('#url_midiateca').val('');
              });
              });
              });";

    $this->defaultXmlnukeDocument->addJavaScriptSource($jsSource);
    // $this->defaultXmlnukeDocument->addJavaScriptMethod("url_midiateca", "onfocus", "$('#caminho_arquivo_midiateca').val('');");
    // $this->defaultXmlnukeDocument->addJavaScriptMethod("caminho_arquivo_midiateca", "onfocus", "$('#url_midiateca').val('');");


    return $this->defaultXmlnukeDocument;
  }

  public function __constructor()
  {

  }

  public function __destruct()
  {

  }

  public function useCache()
  {
    return false;
  }

  public function requiresAuthentication()
  {
    return true;
  }

  public function getAccessLevel()
  {
    return AccessLevel::OnlyRole;
  }

  public function getRole()
  {
    return array("ANALISTA", "EDITOR", "GESTOR", "ADMINISTRADOR");
  }

}

?>
