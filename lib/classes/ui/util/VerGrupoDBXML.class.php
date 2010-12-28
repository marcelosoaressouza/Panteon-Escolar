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

class VerGrupoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;
    protected $_opcao;

    public function generateObject($current)
    {
        $id_grupo_ver = $this->_context->ContextValue("vergrupo");

        $span1 = new XmlnukeSpanCollection();
        $this->addXmlnukeObject($span1);

        if($this->_opcao == "ver")
        {
            $db = new UsuarioXGrupoDB($this->_context);
            $it = $db->obterTodosOsUsuariosPorIDGrupo($id_grupo_ver);
            $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Membros do Grupo:</div><br/>'));

            if(!$it->hasNext())
            {
                $span1->addXmlnukeObject(new XmlNukeText("<b>Nenhum membro definido ainda.</b>"));
            }

            while($it->hasNext())
            {
                $sr = $it->moveNext();
                $usuario = "<b>".$sr->getField("nome_completo_usuario")."</b><br/>";
                $span1->addXmlnukeObject(new XmlNukeText($usuario));
            }


        }

        // Gera Página XML Final
        $node = XmlUtil::CreateChild($current, "blockcenter", "");
        $body = XmlUtil::CreateChild($node, "body", "");

        parent::generatePage($body);

    }

    public function VerGrupoDBXML($context, $opcao)
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
