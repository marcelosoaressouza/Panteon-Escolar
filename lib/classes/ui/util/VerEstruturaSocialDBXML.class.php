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

class VerEstruturaSocialDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;
    protected $_opcao;

    public function generateObject($current)
    {
        $id_estrutura_social_ver = $this->_context->ContextValue("verestruturasocial");

        $span1 = new XmlnukeSpanCollection();
        $this->addXmlnukeObject($span1);
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Estrutura Social (Grupos Sociais):</div><br/>'));

        if($this->_opcao == "ver")
        {
            $db = new GrupoSocialDB($this->_context);
            $it = $db->obterTodosOsGruposSociaisPorIDEstruturaSocial($id_estrutura_social_ver);

            if(!$it->hasNext())
            {
                $span1->addXmlnukeObject(new XmlNukeText("Não há Grupos Sociais Cadastrados"));
            }

            while($it->hasNext())
            {
                $sr = $it->moveNext();
                $grupo_social = $sr->getField("nome_grupo_social");
                $descricao_grupo_social = $sr->getField("descricao_grupo_social");
                $txt  = '<div id="subtitulos"> - '.$grupo_social.'</div>';
                $txt .= '<div id="textover">'.$descricao_grupo_social.'</div><br/>';
                $span1->addXmlnukeObject(new XmlNukeText($txt));

            }


        }

        // Gera Página XML Final
        $node = XmlUtil::CreateChild($current, "blockcenter", "");
        $body = XmlUtil::CreateChild($node, "body", "");

        parent::generatePage($body);

    }

    public function VerEstruturaSocialDBXML($context, $opcao)
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
