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

class ConfigTipoMidiaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;
    protected $_opcao;
    protected $_num_registros_padrao = 3;

    public function generateObject($current)
    {
        $id_usuario = $this->_context->authenticatedUserId();
        $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

        $span1 = new XmlnukeSpanCollection();
        $this->addXmlnukeObject($span1);

        $titulo = "Tipo Midia";
        $nome_modulo = "configtipomidia";

        // Inicio - Area Principal
        //
        if($this->_opcao == "processPageField")
        {
            $nome_modulo = "configtipomidia";

            if($this->_context->ContextValue("acao") == 'ppmsgs')
            {
                $this->_context->redirectUrl($nome_modulo);
            }

            // Mensagem de Avisos
            $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

            $dbUsuarioXNivelAcesso = new UsuarioXNivelAcessoDB($this->_context);
            $nivel_acesso = $dbUsuarioXNivelAcesso->obterNivelAcessoPorIDUsuario($id_usuario);

            if($nivel_acesso == "GESTOR")
            {
                $dbUsuario = new UsuarioDB($this->_context);
                $id_instituicao = $dbUsuario->obterPorId($id_usuario)->getIDInstituicao();

            }

            else if($nivel_acesso == "ADMINISTRADOR")
            {
                $id = "";
            }

            else
            {
                $this->_context->redirectUrl("/meuperfil");
                $id = "";

            }

            $permissao = array(true, false, true, false);

            $dbxml = new TipoMidiaDBXML($this->_context, $nome_modulo, $titulo);
            $pagina = $dbxml->criarProcessPageFields($permissao);

            if($pagina->getAllRecords()->Count() > 0)
            {
                $span1->addXmlnukeObject($pagina);

            }

            else
            {
                $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum registro encontrado.</div>'));

            }

        }

        //
        // Fim - Area Principal

        // Inicio Lista Direita
        //
        if($this->_opcao == "listarDireita")
        {
            $node = XmlUtil::CreateChild($current, "blockmensagem", "");
            $body = PanteonEscolarBaseModule::criarTitulo($node);
            $body = PanteonEscolarBaseModule::preencherBarraVazia($node);

            if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
            {
                XmlUtil::AddAttribute($node, "criartemapanteon", "true");
            }

        }

        //
        // Fim - Lista Direita

        // Inicio - menu
        //
        if($this->_opcao == "menu")
        {
            $node = XmlUtil::CreateChild($current, "blockabausuario", "");
            $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuConfig(PanteonEscolarMenu::TipoMidia, $nivel_acesso));

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
            $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'configinstituicao'));
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
        $formPost = "module:panteonescolar.ConfigTipoMidia";
        $form = new XmlFormCollection($this->_context, $formPost, "Minhas Mensagens");

        $buttons = new XmlInputButtons();
        $buttons->addSubmit("Filtrar");

        $form->addXmlnukeObject($this->filtroConfigTipoMidia());
        $form->addXmlnukeObject($buttons);

        $span->addXmlnukeObject($form);

        return $span;

    }

    public function filtroConfigTipoMidia()
    {
        $listaConfigTipoMidia = array(1 => "Mensagens Enviadas por Mim", 2 => "Mensagens Recebidas");
        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_minhas_mensagens_filtro", "Minhas Mensagens", $listaConfigTipoMidia);

        return $lista;

    }

    public function ConfigTipoMidiaDBXML($context, $opcao)
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