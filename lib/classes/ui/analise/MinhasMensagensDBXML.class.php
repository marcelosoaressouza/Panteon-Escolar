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

class MinhasMensagensDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;
    protected $_opcao;
    protected $_num_registros_padrao = 5;

    public function generateObject($current)
    {
        $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");
        $id_usuario = $this->_context->authenticatedUserId();
        $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

        $span1 = new XmlnukeSpanCollection();
        $this->addXmlnukeObject($span1);

        if($id_tema_panteon == "")
        {
            $this->_context->redirectUrl("/meustemaspanteon");
        }

        if($this->_opcao == "listarDireita")
        {
            $node = XmlUtil::CreateChild($current, "blockmensagem", "");
            $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Mensagens');
            $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'qui, você pode receber e enviar mensagens para seus colegas e professor (a).', '');

            if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
            {
                XmlUtil::AddAttribute($node, "criartemapanteon", "true");
            }

        }

        if($this->_opcao == "processPageField")
        {
            $id_usuario_dest = "";
            $dbxml = new MensagemUsuarioDBXML($this->_context, "minhasmensagens", "Minhas Mensagens");

            $permissao = array(true, true, true, true);

            // Filtro
            if($this->_context->ContextValue("id_minhas_mensagens_filtro") == 2)
            {
                $permissao = array(false, false, false, false, false);
                $id_usuario_dest = $id_usuario;

            }

            else if($this->_context->ContextValue("id_minhas_mensagens_filtro") == 3)
            {
                $permissao = array(false, false, false, false, false);
                $id_usuario_dest = $id_usuario;

            }

            $pagina = $dbxml->criarProcessPageFields($id_usuario, $id_tema_panteon, "", $id_usuario_dest, $permissao);

            if(($pagina->getAllRecords()->Count() > 0) || ($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1))
            {
                if($this->_context->ContextValue("acao") == "")
                {
                    $span1->addXmlnukeObject($this->filtro());
                }

                $span1->addXmlnukeObject($pagina);
            }

            else
            {
                if($this->_context->ContextValue("id_minhas_mensagens_filtro") != 2)
                {
                    $aviso = new XmlInputLabelObjects("<p></p>");
                    $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma Mensagem encontrada, cadastre a primeira agora.<br/> <a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clicando Aqui</a></div>'));
                    $span1->addXmlnukeObject($aviso);

                }

                else
                {
                    $aviso = new XmlInputLabelObjects("<p></p>");
                    $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma Mensagem encontrada, cadastre a primeira agora.</div>'));
                    $span1->addXmlnukeObject($aviso);
                }

                if($this->_context->ContextValue("acao") == "")
                {
                    $span1->addXmlnukeObject($this->filtro());
                }

            }

        }

        // Inicio - menu
        //
        if($this->_opcao == "menu")
        {
            $node = XmlUtil::CreateChild($current, "blockabausuario", "");
            $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::Forum));

        }

        //
        // Fim - menu

        // Inicio - menu head
        //
        if($this->_opcao == "menuHead")
        {
            $nodeHead = XmlUtil::CreateChild($current, "blockhead", "");
            XmlUtil::AddAttribute($nodeHead, "perfil", strtolower($nivel_acesso));

            $msg = "Bem-Vindo, ".ucfirst($this->_context->authenticatedUser())." (".$nivel_acesso.").";
            $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
            $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'meutemapanteon'));
            XmlUtil::AddAttribute($node, "nome_usuario", $msg);
            XmlUtil::AddAttribute($node, "logout", "true");

        }

        //
        // Fim - menu head


        $node = XmlUtil::CreateChild($current, "blockcenter", "");
        $body = XmlUtil::CreateChild($node, "body", "");

        parent::generatePage($body);

    }

    public function filtro()
    {
        $span = new XmlnukeSpanCollection();
        $formPost = "module:panteonescolar.minhasmensagens";
        $form = new XmlFormCollection($this->_context, $formPost, "Minhas Mensagens");
        $form->addXmlnukeObject($this->filtroMinhasMensagens());

        $buttons = new XmlInputButtons();
        $buttons->addSubmit("Pesquisar");
        $form->addXmlnukeObject($buttons);

        $span->addXmlnukeObject($form);

        return $span;

    }

    public function filtroMinhasMensagens()
    {
        $listaMinhasMensagens = array(1 => "Mensagens Enviadas por Mim", 2 => "Mensagens Recebidas");//0 => "-- Selecione --", , 3 => "Todas as Mensagens"

        $id_minhas_mensagens_filtro_selecionada = $this->_context->ContextValue("id_minhas_mensagens_filtro");
        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_minhas_mensagens_filtro", "Minhas Mensagens", $listaMinhasMensagens, $id_minhas_mensagens_filtro_selecionada);

        return $lista;

    }

    public function MinhasMensagensDBXML($context, $opcao)
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