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

class VerPontoDeVistaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current) {
    $id_ponto_de_vista_ver = $this->_context->ContextValue("pontodevista");
    $id_usuario = $this->_context->authenticatedUserId();

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver") {
      $db = new PontodeVistaDB($this->_context);
      $dbSP = new SituacaoProblemaDB($this->_context);
      $dbIA = new ItemAnaliseDB($this->_context);
      $dbS = new SujeitoDB($this->_context);

      $model = new PontodeVistaModel();
      $model = $db->obterPorId($id_ponto_de_vista_ver);

      $id_situacao_problema = $model->getIDSituacaoProblema();
      $id_item_analise = $model->getIDItemAnalise();
      $id_sujeito = $model->getIDSujeito();

      $item_analise = $dbIA->obterPorId($id_item_analise)->getNomeItemAnalise();
      $situacao_problema = $dbSP->obterPorId($id_situacao_problema)->getNomeSituacaoProblema();
      $sujeito = $dbS->obterPorId($id_sujeito)->getNomeSujeito();
      $texto = $model->getTextoPontodeVista();

      $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos">Item Análise</div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<div id="textover">'.$item_analise.'</div><br/>'));

      $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos">Situação-Problema</div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<div id="textover">'.$situacao_problema.'</div><br/>'));

      $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos">Sujeito</div>'));
      $span1->addXmlnukeObject(new XmlNukeText('<div id="textover">'.$sujeito.'</div><br/>'));

      if($id_usuario != "") {
        $db = new UsuarioXPontoDeVistaDB($this->_context);
        $it = $db->obterPorIDUsuarioXPontoDeVista($id_usuario, $id_ponto_de_vista_ver);

        if($it->hasNext()) {
          $sr = $it->moveNext();
          $comentário = $sr->getField('texto_usuario_x_ponto_de_vista');

          $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos">Comentário</div><br/>'));

          if($sr->getField('texto_usuario_x_ponto_de_vista') == "") {
            $span1->addXmlnukeObject(new XmlNukeText('Comentário em Branco.'));

          } else {
            $span1->addXmlnukeObject(new XmlNukeText($comentário));

          }


        }
      }

      $span1->addXmlnukeObject(new XmlNukeText('<br/><br/>'));
      $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos">Ponto de Vista</div><br/>'));
      $span1->addXmlnukeObject(new XmlNukeText($texto));


    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function VerPontoDeVistaDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>
