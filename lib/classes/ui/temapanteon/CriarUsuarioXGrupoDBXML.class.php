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

class CriarUsuarioXGrupoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
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

        $titulo = "Grupo para Usuário";
        $nome_modulo = "criarusuarioxgrupo";
        $id_usuario = $this->_context->authenticatedUserId();
        $id_grupo = $this->_context->getCookie("id_grupo_selecionado");

        // Inicio - Area Principal
        //
        if($this->_opcao == "processPageField")
        {
            $nome_modulo = "criarusuarioxgrupo";

            if($this->_context->ContextValue("acao") == 'ppmsgs')
            {
                $this->_context->redirectUrl($nome_modulo);
            }

            // Mensagem de Avisos
            $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

            $span = new XmlnukeSpanCollection();
            $aviso = new XmlInputLabelObjects("<p></p>");
            $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas"> <a href="criargrupo">Clique aqui para retornar a Criação de Grupo deste Tema Panteon </a></div>'));
            $span1->addXmlnukeObject($aviso);

            if($nivel_acesso =="ADMINISTRADOR")
            {
                $permissao = array(true, false, false, true);
            }

            else
            {
                $permissao = array(true, false, false, false);
            }

            $dbxml = new UsuarioXGrupoDBXML($this->_context, $nome_modulo, $titulo);
            $pagina = $dbxml->criarProcessPageFields($id_usuario, $permissao, $id_grupo);
            $span1->addXmlnukeObject(new XmlNukeBreakLine());

            if($pagina->getAllRecords()->Count() > 0)
            {
                $span1->addXmlnukeObject($pagina);

            }

            else
            {
                if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1))
                {
                    $span1->addXmlnukeObject($pagina);

                }

                else
                {
                    $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Usuário cadastrado neste Grupo, cadastre o primeiro agora. <br/> <a href="/criarusuarioxgrupo&acao=ppnew">Clicando Aqui</a></div>'));

                }

            }

        }

        //
        // Fim - Area Principal

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

        // Inicio - menu
        //
        if($this->_opcao == "menu")
        {
            $node = XmlUtil::CreateChild($current, "blockabausuario", "");
            $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuCriarTemaPanteon(PanteonEscolarMenu::CriarTema));
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
            $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso));
            XmlUtil::AddAttribute($node, "nome_usuario", $msg);
            XmlUtil::AddAttribute($node, "logout", "true");

        }

        //
        // Fim - menu head


        $node = XmlUtil::CreateChild($current, "blockcenter", "");
        $body = XmlUtil::CreateChild($node, "body", "");

        parent::generatePage($body);

    }


    public function CriarUsuarioXGrupoDBXML($context, $opcao)
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