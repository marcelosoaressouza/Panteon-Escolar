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

class MinhasNotasDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
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

        if($this->_opcao == "listarDireita")
        {
            $node = XmlUtil::CreateChild($current, "blockmensagem", "");

            $db = new NotaDB($this->_context);
            $id_usuario = $this->_context->authenticatedUserId();
            $itDB = $db->obterTodasAsNotasPorIDUsuario($id_usuario);

            $body = PanteonEscolarBaseModule::criarTitulo($node, "Dica Minhas Anotações");

            if($itDB->Count() > 0)
            {
                $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "nome_nota", "texto_nota", "nome_completo_usuario");
            }

            else
            {
                $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Aqui, você pode escrever suas anotações sobre o(s) Tema(s) Panteon em análise. Suas anotações podem te ajudar a escrever os diagnósticos e a proposta de ação.', '');
            }

            if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="MEDIADOR"))
            {
                XmlUtil::AddAttribute($node, "criartemapanteon", "true");
            }

        }

        if($this->_opcao == "processPageField")
        {
            $nome_modulo = "minhasnotas";

            if($this->_context->ContextValue("acao") == 'ppmsgs')
            {
                $this->_context->redirectUrl($nome_modulo);
            }

            // Mensagem de Avisos
            $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

            $dbxml = new NotaDBXML($this->_context, "minhasnotas", "Minhas Anotações");
            $permissao = array(true, false, true, false);
            $pagina = $dbxml->criarProcessPageFields($id_usuario, $permissao);

            if($pagina->getAllRecords()->Count() > 0)
            {
                if($this->_context->ContextValue("acao") == "")
                {
                    $span1->addXmlnukeObject($this->filtro($id_usuario));
                }

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
                    $db = new UsuarioXTemaPanteonDB($this->_context);
                    $span1->addXmlnukeObject(new XmlnukeBreakLine());
                    $span1->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma Anotação encontrada, cadastre a primeira agora. <br/> <a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clique Aqui</a></div>'));

                }
            }

            $node = XmlUtil::CreateChild($current, "blockcenter", "");
            $body = XmlUtil::CreateChild($node, "body", "");

        }

        // Inicio - menu
        //
        if($this->_opcao == "menu")
        {
            $node = XmlUtil::CreateChild($current, "blockabausuario", "");
            $body = PanteonEscolarBaseModule::preencherMenuPadrao($node, PanteonEscolarMenu::Anotacoes);
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
            $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'meuperfil'));
            XmlUtil::AddAttribute($node, "nome_usuario", $msg);
            XmlUtil::AddAttribute($node, "logout", "true");

        }

        //
        // Fim - menu head


        parent::generatePage($body);

    }

    public function filtro($id_usuario)
    {
        $span = new XmlnukeSpanCollection();
        $formPost = "module:panteonescolar.minhasnotas";
        $form = new XmlFormCollection($this->_context, $formPost, "Minhas Notas");
        $form->addXmlnukeObject($this->filtroMeusTemasPanteon($id_usuario));

        $buttons = new XmlInputButtons();
        $buttons->addSubmit("Pesquisar");
        $form->addXmlnukeObject($buttons);

        $span->addXmlnukeObject($form);

        return $span;

    }

    public function filtroMeusTemasPanteon($id_usuario)
    {
        $db = new UsuarioXTemaPanteonDB($this->_context);
        $it = $db->obterTodosOsTemasPanteonPorIDUsuario($id_usuario);
        $lista = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_tema_panteon", "nome_tema_panteon");
        $lista[''] = 'Todas as Notas';

        $id_tema_panteon_filtro_selecionado = $this->_context->ContextValue("id_tema_panteon_filtro");
        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_tema_panteon_filtro", "Tema Panteon", $lista, $id_tema_panteon_filtro_selecionado);

        return $lista;

    }

    public function MinhasNotasDBXML($context, $opcao)
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