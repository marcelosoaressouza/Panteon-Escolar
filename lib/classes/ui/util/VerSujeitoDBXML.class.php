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

        $foto_sujeito = $db->obterPorId($id_sujeito_ver)->getCaminhoFotoSujeito();
        $nome_sujeito = $db->obterPorId($id_sujeito_ver)->getNomeSujeito();
        $desc_sujeito = $db->obterPorId($id_sujeito_ver)->getDescricaoSujeito();
        $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos"><img alt="Imagem" width="100" src="'.$foto_sujeito.'"/></div>'));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">'.$nome_sujeito.'</div><br/>'));
        $span1->addXmlnukeObject(new XmlNukeText($desc_sujeito."<br/>"));
        //$txt  = '<div id="subtitulos"> - Meus Pontos de Vista</div>';
        //$span1->addXmlnukeObject(new XmlNukeText($txt));
        /*
        $dbPontoDeVista = new PontodeVistaDB($this->_context);

        $itPontoDeVista= $dbPontoDeVista->obterTodosOsPontosDeVistaComItemAnaliseESituacaoProblemaPorIDSujeito($id_sujeito_ver);
        while($itPontoDeVista->hasNext()) {
            $srPontoDeVista = $itPontoDeVista->moveNext();
            $desc_ponto_de_vista = $srPontoDeVista->getField("texto_ponto_de_vista");
            $desc_item_analise = $srPontoDeVista->getField("nome_item_analise");
            $desc_situacao_problema = $srPontoDeVista->getField("nome_situacao_problema");
            $txt .= '<div id="textover">'.$nome_sujeito.'</div>';
            $txt  = '<table>
                    <tr>
                        <td width="60%"><b>Ponto de Vista</b></td>
                        <td width="15%"><b>Item Ánalise</b></td>
                        <td width="25%"><b>Situação Problema</b></td>
                    </tr>
                    <tr>
                        <td>'.$desc_ponto_de_vista.'</td>
                        <td>'.$desc_item_analise.'</td>
                        <td>'.$desc_situacao_problema.'</td>
                    </tr>
                 </table>';
            $span1->addXmlnukeObject(new XmlNukeText($txt));
        }
         *
         */



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

          //return '<img alt="Imagem" width="100" src="'.$sr->getField("caminho_foto_sujeito").'"/>';
          $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos"><img alt="Imagem" width="100" src="'.$sr->getField("caminho_foto_sujeito").'"/></div>'));
          $url_link = $this->_context->bindModuleUrl("panteonescolar.versujeito");
          //$span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos"><a href="'.$url_link.'&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;versujeito='.$sr->getField("id_sujeito").'">'.$sr->getField("nome_sujeito").'</a></div>'));
          $span1->addXmlnukeObject(new XmlNukeText('<div id ="subtitulos">'.$sr->getField("nome_sujeito").'</div>'));
          $span1->addXmlnukeObject(new XmlNukeText('<div id="textover">'.$grupo_social.'</div>'));
          $span1->addXmlnukeObject(new XmlNukeText('<div id="textover">'.$sr->getField("descricao_sujeito").'</div><br/>'));

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
