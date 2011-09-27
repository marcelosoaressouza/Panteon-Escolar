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

class VerColetarDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;

  public function generateObject($current)
  {
    $id_usuario = $this->_context->authenticatedUserId();
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

    if($this->_context->ContextValue("vercoletar") != "")
    {
      $id_coletar = $this->_context->ContextValue("vercoletar");
    }

    else
    {
      $id_coletar = $this->_context->ContextValue("verdescartar");
    }

    $pagina = $this->_context->ContextValue("pagina");
    $coletar = $this->_context->ContextValue("coletar");

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($this->_opcao == "ver")
    {

      // $dbxml = new UsuarioXPontoDeVistaDBXML($this->_context, "vercoletar", "Ver Coletar");
      // $permissao = array (true, false, false, false);
      // $pagina = $dbxml->criarProcessPageFieldsColetar($id_usuario, $id_coletar, $id_tema_panteon, $coletar, $permissao);
      // if ($id_coletar != "") $pagina->forceCurrentAction("ppnew");


      $span = new XmlnukeSpanCollection();

      if($this->_context->ContextValue("chamada") == "")
      {
        if($this->_context->ContextValue("vercoletar") != "")
        {
          $coletar = 1;
        }

        else
        {
          $coletar = 0;
        }

        $formPost = "module:panteonescolar.vercoletar&amp;chamada=1&amp;xsl=page&amp;pagina=$pagina";

        $form = new XmlFormCollection($this->_context, $formPost, "Coletar/Descartar");

        $comentario = new XmlInputMemo("Comentário: ", "texto_usuario_x_ponto_de_vista", NULL, 60);
        $comentario->setSize(70,13);
        $form->addXmlnukeObject($comentario);

        $vercoletar = new XmlInputHidden("id_ponto_de_vista", $id_coletar);
        $form->addXmlnukeObject($vercoletar);

        $vercoletar = new XmlInputHidden("coletar", $coletar);
        $form->addXmlnukeObject($vercoletar);

        $buttons = new XmlInputButtons();
        $buttons->addSubmit("Coletar Dados");
        $form->addXmlnukeObject($buttons);

        $span1->addXmlnukeObject($form);

        //Debug::PrintValue($id_usuario."-".$id_tema_panteon."-".$id_coletar."-".$coletar);

      }

      else
      {
        $db = new UsuarioXPontoDeVistaDB($this->_context);
        $model = new UsuarioXPontoDeVistaModel();
        $model->setIDUsuario($id_usuario);
        $model->setIDTemaPanteon($id_tema_panteon);
        $model->setIDPontodeVista($this->_context->ContextValue("id_ponto_de_vista"));
        $model->setColetadoUsuarioXPontoDeVista($this->_context->ContextValue("coletar"));
        $model->setTextoUsuarioXPontoDeVista($this->_context->ContextValue("texto_usuario_x_ponto_de_vista"));

        $db->cadastrar($model);

        $this->_context->addCookie("mensagem_aviso", "Ponto de Vista Coletado/Descartado");
        $this->_context->redirectUrl("module:panteonescolar.todospontodevistatemapanteon&amp;pagina=$pagina");

      }

    }

    // Gera Página XML Final
    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);


  }

  public function VerColetarDBXML($context, $opcao)
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
