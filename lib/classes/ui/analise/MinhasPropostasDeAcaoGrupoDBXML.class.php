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

class MinhasPropostasDeAcaoGrupoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;
    protected $_opcao;
    protected $_num_registros_padrao = 3;

    public function generateObject($current)
    {
        $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");

        $id_usuario = $this->_context->authenticatedUserId();
        $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

        if($id_tema_panteon == "")
        {
            $this->_context->redirectUrl("/meustemaspanteon");
        }

        $span1 = new XmlnukeSpanCollection();
        $this->addXmlnukeObject($span1);

        if($this->_opcao == "processPageField")
        {
            $dbGrupo = new UsuarioXGrupoDB($this->_context);
            $id_grupo = $dbGrupo->obterIDGrupoPorIDUsuarioXIDTemaPanteon($id_usuario, $id_tema_panteon);

            if($id_grupo == "")
            {
                $this->_context->addCookie("mensagem_aviso", "<b>Você ainda não pertence a um Grupo</b>");
                $this->_context->redirectUrl("/minhaspropostasdeacao");
            }

            if($this->_context->ContextValue("Pesquisar") == true && $this->_context->ContextValue("id_grupo_filtro")!="")
            {
                $id_grupo = $this->_context->ContextValue("id_grupo_filtro");
            }

            else if($id_grupo == "")
            {
                $id_grupo = 65535;
            }

            $dbxml = new PropostaDeAcaoGrupoDBXML($this->_context, "minhaspropostasdeacaogrupo", "Minhas Propostas de Ação em Grupo");

            // permissao - $newRec, $view, $edit, $delete
            $permissao = array(true, false, true, false);
            $pagina = $dbxml->criarProcessPageFields($id_grupo, $permissao);

            if($pagina->getAllRecords()->Count() > 0)
            {
                if($this->_context->ContextValue("acao") == "")
                {
                    $span1->addXmlnukeObject($this->filtro());
                }

                $span1->addXmlnukeObject($pagina);

                $db = new UsuarioXGrupoDB($this->_context);
                $it = $db->obterTodosOsUsuariosPorIDGrupo($id_grupo);

                $span1->addXmlnukeObject(new XmlNukeText("<div style='float: right; text-align: right;'>"));
                $span1->addXmlnukeObject(new XmlNukeText('<div style="font-size:21px; font-weight:700; color:#A42112;">Fazem parte do seu Grupo</div>'));

                while($it->hasNext())
                {
                    $sr = $it->moveNext();
                    $usuario = "<b>".$sr->getField("nome_completo_usuario")."</b><br/>";

                    $span1->addXmlnukeObject(new XmlNukeText($usuario));

                }

                $span1->addXmlnukeObject(new XmlNukeText("</div>"));

                $grupo = "<b>".$sr->getField("nome_grupo")."</b><br/>";

            }

            else
            {

                if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1))
                {
                    $span1->addXmlnukeObject($pagina);
                }

                else
                {
                    $span1->addXmlnukeObject($this->filtro());
                    $msg = '<div id="meusPontosDeVistas"> Nenhuma Proposta de Ação em Grupo encontrada, cadastre a primeira agora. <br/><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=ppnew">Clicando Aqui</a></div>';

                    if($id_grupo == 65535)
                    {
                        $msg = "Você ainda não faz parte de um grupo";
                    }

                    $span1->addXmlnukeObject(new XmlNukeText($msg));

                }

            }




        }

        if($this->_opcao == "listarDireita")
        {
            $db = new UsuarioXPontoDeVistaDB($this->_context);
            $itDB = $db->obterTodosOsPontoDeVistaPorIDTemaPanteonXIDUsuario($id_tema_panteon, $id_usuario, 2, 1);
            $node = XmlUtil::CreateChild($current, "blockmensagem", "");
            $body = PanteonEscolarBaseModule::criarTitulo($node, "Últimos Coletados");

            if($itDB->Count() > 0)
            {
                $body = PanteonEscolarBaseModule::preencherBarra($node, $itDB, "", "texto_ponto_de_vista", "nome_sujeito", PanteonEscolarConsts::PontoDeVista_URL, "id_ponto_de_vista");
            }

            else
            {
                $body = PanteonEscolarBaseModule::preencherBarraVazia($node);
            }

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
            $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::PlanoDeAcao));

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

        $formPost = "module:panteonescolar.minhaspropostasdeacaogrupo";
        $form = new XmlFormCollection($this->_context, $formPost, "Propostas de Ação de Grupo");

        //$form->addXmlnukeObject($this->filtroDescricao());
        $form->addXmlnukeObject($this->filtroGrupo());
        $form->addXmlnukeObject(new XmlInputHidden("Pesquisar", true));
        //$form->addXmlnukeObject($this->filtroEstruturaSocial());

        $buttons = new XmlInputButtons();
        $buttons->addSubmit("Pesquisar");
        $form->addXmlnukeObject($buttons);

        $span->addXmlnukeObject($form);

        return $span;

    }

    public function filtroGrupo()
    {
        $db = new UsuarioXGrupoDB($this->_context);
        $it = $db->obterTodosGruposPorIdTemaPanteon($this->_context->getCookie("id_tema_panteon_definido"));
        $listaGrupo = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_grupo", "nome_grupo");
        $listaGrupo[""] = "Propostas de Ação de Grupo";

        $id_grupo_filtro_selecionado = $this->_context->ContextValue("id_grupo_filtro");
        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_grupo_filtro", "Grupo", $listaGrupo, $id_grupo_filtro_selecionado);


        return $lista;

    }

    public function MinhasPropostasDeAcaoGrupoDBXML($context, $opcao)
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