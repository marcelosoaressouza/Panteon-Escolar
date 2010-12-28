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

class BibliotecaPublicaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

    protected $_context;
    protected $_opcao;
    protected $_num_registros_padrao = 4;

    public function generateObject($current)
    {

        if($this->_context->IsAuthenticated())
        {
            $this->_context->redirectUrl("/meuperfil");
        }

        $span1 = new XmlnukeSpanCollection();
        $this->addXmlnukeObject($span1);

        if($this->_opcao == "listarDireita")
        {
            $txt = "Para ter acesso completo ao contéudo dos Temas Panteon aqui listados efetive seu cadastro na opção <a href='/xmlnuke.php?module=login&amp;action=action.NEWUSER&amp;ReturnUrl=%2fcriartemapanteon&amp;site=PanteonEscolar&amp;xsl=page&amp;xml=home&amp;lang=pt-br'>Cadastre-se</a>";
            $node = XmlUtil::CreateChild($current, "blockmensagem", "");
            $body = PanteonEscolarBaseModule::criarTitulo($node, "Como tenho Acesso?");
            $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, "", $txt, "");

        }

        if($this->_opcao == "processPageField")
        {
            $dbxml = new TemaPanteonDBXML($this->_context, "bibliotecapublica", "Biblioteca Pública de Temas Panteon");

            // permissao - $newRec, $view, $edit, $delete
            $permissao = array(false, false, false, false);
            $pagina_BibliotecaPublica = $dbxml->criarProcessPageFields($permissao, 3, "", false, false, true, "", true);

            if($pagina_BibliotecaPublica->getAllRecords()->Count() > 0)
            {
                if($this->_context->ContextValue("acao") == "")
                {
                    $span1->addXmlnukeObject($this->filtro());
                }

                $span1->addXmlnukeObject($pagina_BibliotecaPublica);

            }

            else
            {
                $aviso = new XmlInputLabelObjects("<p></p>");
                $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhum Tema Panteon encontrado.</div>'));
                $span1->addXmlnukeObject($aviso);

                if($this->_context->ContextValue("acao") == "")
                {
                    $span1->addXmlnukeObject($this->filtro());
                }
            }


        }

        // Inicio - menu head
        //
        if($this->_opcao == "menuHead")
        {
            $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
            $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadInicial('bibliotecapublica'));
            $body = PanteonEscolarBaseModule::preencherMenuHeadAuxiliar($node, PanteonEscolarBaseModule::preencherMenuHeadInicialAcesso());

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

        $script  = '<script type="text/javascript">';
        $script .= ' $("#formGeralLabelid_metodo_analise_filtro").hide(); $("#id_metodo_analise_filtro").hide();';
        $script .= ' $("#formGeralLabelid_estrutura_social_filtro").hide(); $("#id_estrutura_social_filtro").hide();';
        $script .= '</script>';

        $script .= '<script type="text/javascript">';
        $script .= '$("#filtro").click(function () {';
        $script .= '  if ( $("#formGeralLabelid_metodo_analise_filtro").is(":hidden") ) {';
        $script .= '    $("#formGeralLabelid_metodo_analise_filtro").show(); $("#id_metodo_analise_filtro").show();';
        $script .= '    $("#formGeralLabelid_estrutura_social_filtro").show(); $("#id_estrutura_social_filtro").show();';
        $script .= '    $("#filtro").html("Busca simples");';
        $script .= '  }';
        $script .= ' else {';
        $script .= '    $("#formGeralLabelid_metodo_analise_filtro").hide(); $("#id_metodo_analise_filtro").hide();';
        $script .= '    $("#formGeralLabelid_estrutura_social_filtro").hide(); $("#id_estrutura_social_filtro").hide();';
        $script .= '    $("#filtro").html("Busca avançada");';
        $script .=  '}';
        $script .= '});';
        $script .= '</script>';

        $url = new XmlInputLabelObjects("<p></p>");
        $url->addXmlnukeObject(new XmlNukeText('<a id="filtro" href="#">Busca avançada</a><br/>'));
        $span->addXmlnukeObject($url);
        $span->addXmlnukeObject(new XmlNukeText($script));

        $formPost = "module:panteonescolar.bibliotecapublica";
        $form = new XmlFormCollection($this->_context, $formPost, "Bilbioteca Pública de Temas Panteon");

        $form->addXmlnukeObject($this->filtroDescricao());
        $form->addXmlnukeObject($this->filtroMetodoAnalise());
        $form->addXmlnukeObject($this->filtroEstruturaSocial());

        $buttons = new XmlInputButtons();
        $buttons->addSubmit("Pesquisar");
        $form->addXmlnukeObject($buttons);

        $span->addXmlnukeObject($form);

        return $span;

    }

    public function filtroMetodoAnalise()
    {
        $db = new MetodoAnaliseDB($this->_context);
        $it = $db->obterTodos();
        $listaMetodoAnalise = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_metodo_analise", "nome_metodo_analise");
        $listaMetodoAnalise[""] = "Todos os Metódos de Análise";

        $id_metodo_analise_filtro_selecionado = $this->_context->ContextValue("id_metodo_analise_filtro");
        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_metodo_analise_filtro", "Metódo Análise", $listaMetodoAnalise, $id_metodo_analise_filtro_selecionado);

        return $lista;

    }

    public function filtroEstruturaSocial()
    {
        $db = new EstruturaSocialDB($this->_context);
        $it = $db->obterTodos();
        $listaEstruturaSocial = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_estrutura_social", "nome_estrutura_social");
        $listaEstruturaSocial[""] = "Todas as Estruturas Sociais";

        $id_estrutura_social_filtro_selecionado = $this->_context->ContextValue("id_estrutura_social_filtro");
        $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_estrutura_social_filtro", "Estrutura Social", $listaEstruturaSocial, $id_estrutura_social_filtro_selecionado);

        return $lista;

    }

    public function filtroDescricao()
    {
        return new XmlInputTextBox("Texto: ", "descricao_tema_panteon_filtro", NULL, 40);

    }

    public function BibliotecaPublicaDBXML($context, $opcao)
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