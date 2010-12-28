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

class PontoDeVistaTemaPanteonDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;
    protected $_opcao;
    protected $_num_registros_padrao = 2;

    public function generateObject($current)
    {
        $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");
        $data_hora_agora = date("Y-m-d H:i:s");

        $id_usuario = $this->_context->authenticatedUserId();
        $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

        $span1 = new XmlnukeSpanCollection();
        $this->addXmlnukeObject($span1);

        if($this->_opcao == "processPageField")
        {
            $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

            if($this->_context->ContextValue("acao") == "verPontoDeVista")
            {

                $idSujeito = $this->_context->ContextValue("valueid");

                if($idSujeito != "")
                {
                    $this->_context->addCookie("id_sujeito_definido", $idSujeito);
                }

                if($this->_context->getCookie("id_sujeito_definido") != "")
                {
                    $idSujeito = $this->_context->getCookie("id_sujeito_definido");
                }

                $dbSujeito = new SujeitoDB($this->_context);
                $modelSujeito = new SujeitoModel();
                $modelSujeito = $dbSujeito->obterPorId($idSujeito);

                $dbPontoDeVista = new PontoDeVistaDB($this->_context);
                $modelPontoDeVista = new PontoDeVistaModel();
                $modelPontoDeVista = $dbPontoDeVista->obterTodosOsPontosDeVistaPorIDSujeito($idSujeito);

                $dbxml = new PontodeVistaDBXML($this->_context, "pontodevistatemapanteon", "Ponto de Vista do Tema Panteon");

                $permissao = array(false, false, false, false);
                $paginaPontoDeVista = $dbxml->criarProcessPageFields($idSujeito, $permissao, $id_usuario);


                if($paginaPontoDeVista->getAllRecords()->Count() > 0)
                {
                    $span1->addXmlnukeObject($this->filtro($id_tema_panteon, $idSujeito));
                    $span1->addXmlnukeObject($paginaPontoDeVista);

                }

                else
                {
                    $aviso = new XmlInputLabelObjects("<p></p>");
                    $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Ponto de Vista encontrado</div>'));
                    $span1->addXmlnukeObject($aviso);
                    $span1->addXmlnukeObject($this->filtro($id_tema_panteon, $idSujeito));
                }

            }

            else if($this->_context->ContextValue("acao") == "coletarPontoDeVista")
            {

                $idPontoDeVista = $this->_context->ContextValue("valueid");

                $dbPontoDeVista = new PontoDeVistaDB($this->_context);
                $modelPontoDeVista = new PontoDeVistaModel();
                $modelPontoDeVista = $dbPontoDeVista->obterPorId($idPontoDeVista);

                $dbUsuarioXPontoDeVista = new UsuarioXPontoDeVistaDB($this->_context);
                $modelUsuarioXPontoDeVista = new UsuarioXPontoDeVistaModel();

                $modelUsuarioXPontoDeVista->setIDPontoDeVista($idPontoDeVista);
                $modelUsuarioXPontoDeVista->setIDUsuario($id_usuario);
                $modelUsuarioXPontoDeVista->setIDTemaPanteon($id_tema_panteon);
                $modelUsuarioXPontoDeVista->setDataHoraUsuarioXPontoDeVista($data_hora_agora);

                if($this->_context->ContextValue("subacao") == 'descartar')
                {
                    $msgacao = "<b>Ponto de vista descartado:</b> ";
                    $modelUsuarioXPontoDeVista->setColetadoUsuarioXPontoDeVista(0);
                }

                else
                {
                    $msgacao = "<b>Ponto de vista coletado:</b> ";
                    $modelUsuarioXPontoDeVista->setColetadoUsuarioXPontoDeVista(1);
                }

                $count = $dbUsuarioXPontoDeVista->verDuplicado($modelUsuarioXPontoDeVista);

                if($count == 0)
                {
                    $modelUsuarioXPontoDeVista = $dbUsuarioXPontoDeVista->cadastrar($modelUsuarioXPontoDeVista);
                    $this->_context->addCookie("mensagem_aviso", $msgacao.$modelPontoDeVista->getTextoPontodeVista());
                    $this->_context->redirectUrl("/pontodevistatemapanteon");

                }

                else
                {
                    $this->_context->addCookie("mensagem_aviso", "Este Ponto de Vista já foi coletado ou descartado");
                    $this->_context->redirectUrl("/pontodevistatemapanteon");

                }

            }

            else
            {

                $dbxml = new SujeitoDBXML($this->_context, "pontodevistatemapanteon", "Sujeitos com Ponto de Vista");

                // permissao - $newRec, $view, $edit, $delete
                $permissao = array(false, false, false, false);

                $paginaSujeito = $dbxml->criarProcessPageFields($id_tema_panteon, $permissao);

                if($paginaSujeito->getAllRecords()->Count() > 0)
                {
                    $aviso = new XmlInputLabelObjects("<p></p>");
                    $aviso->addXmlnukeObject(PanteonEscolarBaseModule::meusPontosDeVistas($id_usuario, $id_tema_panteon, $this->_context));
                    $span1->addXmlnukeObject($aviso);

                    $span1->addXmlnukeObject($this->filtroSujeito($id_tema_panteon));

                    $span1->addXmlnukeObject($paginaSujeito);

                }

                else
                {
                    $aviso = new XmlInputLabelObjects("<p></p>");
                    $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Ponto de Vista encontrado</div>'));
                    $span1->addXmlnukeObject($aviso);
                    $span1->addXmlnukeObject($this->filtroSujeito($id_tema_panteon));
                }

            }

        }

        if($this->_opcao == "listarDireita")
        {
            $db = new UsuarioXPontoDeVistaDB($this->_context);
            $itDB = $db->obterTodosOsPontoDeVistaPorIDTemaPanteonXIDUsuario($id_tema_panteon, $id_usuario, 2, 1);
            $node = XmlUtil::CreateChild($current, "blockmensagem", "");
            $body = PanteonEscolarBaseModule::criarTitulo($node, "Coletados");

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
            $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::PontoDeVista));

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

    public function filtro($id_tema_panteon, $id_sujeito)
    {
        $span = new XmlnukeSpanCollection();
        $formPost = "module:panteonescolar.pontodevistatemapanteon&amp;acao=verPontoDeVista&amp;valueid=".$id_sujeito;
        $form = new XmlFormCollection($this->_context, $formPost, "Ver Ponto de Vista");

        $form->addXmlnukeObject($this->filtroItemAnalise($id_tema_panteon, $id_sujeito));
        $form->addXmlnukeObject($this->filtroSituacaoProblema($id_tema_panteon, $id_sujeito));

        $span->addXmlnukeObject($form);

        return $span;

    }

    public function filtroSujeito($id_tema_panteon)
    {
        $span = new XmlnukeSpanCollection();
        $formPost = "module:panteonescolar.pontodevistatemapanteon";
        $form = new XmlFormCollection($this->_context, $formPost, "Sujeito Tema Panteon");
        $form->addXmlnukeObject($this->filtroGrupoSocial($id_tema_panteon));

        $span->addXmlnukeObject($form);

        return $span;

    }

    public function filtroItemAnalise($id_tema_panteon, $id_sujeito)
    {
        $temapanteon = new TemaPanteonDB($this->_context);
        $itemanalise = new ItemAnaliseDB($this->_context);
        $modelTemaPanteon = new TemaPanteonModel();

        $modelTemaPanteon = $temapanteon->obterPorId($id_tema_panteon);

        $it = $itemanalise->obterTodosOsItensAnalisePorIDMetodoAnalise($modelTemaPanteon->getIDMetodoAnalise());

        $listaItemAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_item_analise", "nome_item_analise");
        $listaItemAnalise["All"] = "Todos Itens de Análise";

        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_item_analise_filtro", "Item Análise", $listaItemAnalise);

        return $lista;

    }

    public function filtroSituacaoProblema($id_tema_panteon, $id_sujeito)
    {
        $situacaoproblema = new SituacaoProblemaDB($this->_context);
        $it = $situacaoproblema->obterTodasAsSituacoesProblemasPorIDTemaPanteon($id_tema_panteon);

        $listaSituacaoProblem = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_situacao_problema", "nome_situacao_problema");
        $listaSituacaoProblem["All"] = "Todos Itens de Análise";

        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_situacao_problema_filtro", "Situação-Problema", $listaSituacaoProblem);

        return $lista;

    }

    public function filtroGrupoSocial($id_tema_panteon)
    {
        $temapanteon = new TemaPanteonDB($this->_context);
        $gruposocial = new GrupoSocialDB($this->_context);

        $id_estrutura_social = $temapanteon->obterPorId($id_tema_panteon)->getIDEstruturaSocial();

        $itGrupoSocial = $gruposocial->obterTodosOsGruposSociaisPorIDEstruturaSocial($id_estrutura_social);

        $listaGrupoSocial = PanteonEscolarBaseDBAccess::getArrayFromIterator($itGrupoSocial, "id_grupo_social", "nome_grupo_social");
        $listaGrupoSocial[2048] = 'Todos';

        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_grupo_social_filtro", "Grupo Social", $listaGrupoSocial);

        return $lista;

    }

    public function PontoDeVistaTemaPanteonDBXML($context, $opcao)
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
