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

class ApagarDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id = $this->_context->ContextValue("apagar");
    $oque = $this->_context->ContextValue("oque");
    $retorno = $this->_context->ContextValue("returnurl");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {
      if($id != "")
      {

        if($this->_context->ContextValue("acao") != 'delete')
        {
          $url = '/apagar&amp;oque='.$oque.'&amp;returnurl='.$retorno.'&amp;apagar='.$id.'&amp;acao=delete';
          $span1->addXmlnukeObject(new XmlNukeText('<br/><div id ="titulo_confirma">Tem Certeza que deseja Excluir?</div>'));
          $span1->addXmlnukeObject(new XmlNukeText('<div style="color: #A92D1E; font-size: 21px; font-weight:900;"><a href="./'.$url.'">(Sim)</a> ou <a href="./'.$retorno.'" id="fecharfancy">(Não)</a></div>'));
        }

        else
        {
          $db = new GeralDB($this->_context);
          $db->excluir($id, $oque);

          if($oque == 'usuario_x_tema_panteon')
          {
            $this->_context->removeCookie("id_tema_panteon_definido");
            $this->_context->removeCookie("nome_tema_panteon_definido");
            $this->_context->addCookie("mensagem_aviso", "Tema Panteon excluído");

          }

          else
          {
            $this->_context->addCookie("mensagem_aviso", "Excluído");

          }

          $this->_context->redirectUrl($retorno);

        }


      }

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function ApagarDBXML($context, $opcao)
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
