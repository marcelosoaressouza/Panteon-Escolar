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

class ContatoDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
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

        $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

        if($this->_opcao == "listarDireita")
        {
            $txt = "Para ter acesso completo ao contéudo dos Temas Panteon aqui listados efetive seu cadastro no Panteon Escolar.";
            $node = XmlUtil::CreateChild($current, "blockmensagem", "");
            $body = PanteonEscolarBaseModule::criarTitulo($node, "Você Sabia?");
            $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, "", $txt, "");

        }

        if($this->_opcao == "processPageField")
        {
            $formPost = "module:panteonescolar.contato&amp;chamada=1";
            $form = new XmlFormCollection($this->_context, $formPost, "Contato");
            $email = new XmlInputTextBox("E-mail para Contato ", "email_contato", $this->_context->ContextValue("email_contato"), 36);
            $email->setInputTextBoxType(InputTextBoxType::TEXT);
            $email->setDataType(INPUTTYPE::EMAIL);
            $email->setRequired(true);

            $texto = new XmlInputMemo("Dúvida ou Sugestão?", "texto_contato", $this->_context->ContextValue("texto_contato"));
            $texto->setVisualEditor(false);

            $buttons = new XmlInputButtons();
            $buttons->addSubmit("Enviar");
            $form->addXmlnukeObject($email);
            $form->addXmlnukeObject($texto);
            $form->addXmlnukeObject($buttons);

            if($this->_context->ContextValue("chamada") == 1)
            {
                if(($this->_context->ContextValue("email_contato") != "") && ($this->_context->ContextValue("texto_contato") != ""))
                {
                    $aviso = new XmlInputLabelObjects("<p></p>");
                    $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Obrigado pelo Contato.</div>'));
                    $span1->addXmlnukeObject($aviso);

                    MailUtil::Mail($this->_context, PanteonEscolarConsts::Contato, $this->_context->ContextValue("email_contato"), "Contato", "", "", $this->_context->ContextValue("texto_contato"));

                }

                else
                {
                    $aviso = new XmlInputLabelObjects("<p></p>");
                    $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Preencha todos os Campos.</div>'));
                    $span1->addXmlnukeObject($aviso);

                }

            }


            $txt = '<br/><img alt="Imagem Central" title="Imagem Rodapé" src="static/images/imagem_central-baixo.jpg"></img>';

            $span1->addXmlnukeObject($form);
            $span1->addXmlnukeObject(new XmlNukeText($txt));

        }


        // Inicio - menu head
        //
        if($this->_opcao == "menuHead")
        {
            $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
            $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadInicial('contato'));
            $body = PanteonEscolarBaseModule::preencherMenuHeadAuxiliar($node, PanteonEscolarBaseModule::preencherMenuHeadInicialAcesso());

        }

        //


        $node = XmlUtil::CreateChild($current, "blockcenter", "");
        $body = XmlUtil::CreateChild($node, "body", "");

        parent::generatePage($body);

    }

    public function ContatoDBXML($context, $opcao)
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
