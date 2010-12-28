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

class CriarPontoDeVistaTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;
    protected $_opcao;
    protected $_num_registros_padrao = 5;

    public function generateObject($current)
    {
        $id_tema_panteon_ponto_de_vista = $this->_context->getCookie("id_tema_panteon_ponto_de_vista");
        $id_estrutura_social_ponto_de_vista = $this->_context->getCookie("id_estrutura_social_ponto_de_vista");

        $id_usuario = $this->_context->authenticatedUserId();
        $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

        $data_hora_agora = date("Y-m-d H:i:s");

        $span1 = new XmlnukeSpanCollection();
        $this->addXmlnukeObject($span1);

        $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

        if($this->_opcao == "listarDireita")
        {
            $node = XmlUtil::CreateChild($current, "blockmensagem", "");
            $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Sujeito');
            $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Os sujeitos são pessoas que têm envolvimento com o tema. Portanto, emitem seu ponto de vista a respeito das situações-problema do tema. Cada sujeito faz parte de um grupo social.', '');

            if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
            {
                XmlUtil::AddAttribute($node, "criartemapanteon", "true");
            }

        }

        if($this->_opcao == "processPageField")
        {
            $nome_modulo = "criarpontodevistatemapanteon";

            if($this->_context->ContextValue("acao") == 'ppmsgs')
            {
                $this->_context->redirectUrl($nome_modulo);
            }

            // Mensagem de Avisos
            $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

            $dbxml = new SujeitoDBXML($this->_context, "criarpontodevistatemapanteon", "Criar Sujeito(s)");

            if($this->_context->ContextValue("acao") == "criarPontoDeVistaSujeito")
            {
                $this->_context->addCookie("id_sujeito_tema_panteon", $this->_context->ContextValue("valueid"));
                $this->_context->redirectUrl("/criarpontodevistasujeitotemapanteon");

            }

            $permissao = array(true, false, true,false);
            $pagina = $dbxml->criarProcessPageFieldsTemaPanteon($id_tema_panteon_ponto_de_vista, $id_estrutura_social_ponto_de_vista, $permissao);

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
                    $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Sujeito encontrado, cadastre o primeiro agora. <br/><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clicando Aqui</a></div>'));
                }
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

    public function CriarPontoDeVistaTemaPanteonDBXML($context, $opcao)
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