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

ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseModule.class.php");

class OQueE extends PanteonEscolarBaseModule
{

  public function CreatePage()
  {
    $myWords = $this->WordCollection();
    //if($this->_context->IsAuthenticated()) $this->_context->redirectUrl("module:panteonescolar.meuperfil");

    $this->defaultXmlnukeDocument = new XmlnukeDocument("O Que é o Panteon Escolar?", "O Que é o Panteon Escolar?");

    $blockHead   = new XmlBlockCollection(NULL, BlockPosition::Right);
    $blockCenter = new XmlBlockCollection(NULL, BlockPosition::Center);
    $blockInfo   = new XmlBlockCollection(NULL, BlockPosition::Left);
    $blockMenu   = new XmlBlockCollection(NULL, BlockPosition::Menu);

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockHead);
    $blockHead->addXmlnukeObject(new OQueEDBXML($this->_context, "menuHead"));

    $this->defaultXmlnukeDocument->addXmlnukeObject($blockInfo);

    if($this->_context->IsAuthenticated())
    {
      $blockInfo->addXmlnukeObject(new OQueEDBXML($this->_context, "listarDireita"));
    }

    else
    {
      $this->FormLogin($blockInfo);
    }

    //if($this->_context->IsAuthenticated()){
    //$this->defaultXmlnukeDocument->addXmlnukeObject($blockCenter);
    //$blockCenter->addXmlnukeObject(new OQueEDBXML($this->_context, "imagemApresentacao"));
    //}else{
    $this->defaultXmlnukeDocument->addXmlnukeObject($blockCenter);
    $blockCenter->addXmlnukeObject(new OQueEDBXML($this->_context, "processPageField"));
    //}


    return $this->defaultXmlnukeDocument;

  }

//    /**
//     * Form Login
//     *
//     */
//    public function FormLogin()
//    {
//        $myWords = $this->WordCollection();
//
//        $paragraph = new XmlParagraphCollection();
//        $this->_blockCenter->addXmlnukeObject($paragraph);
//
//        //$this->_blockCenter->addXmlnukeObject(new XmlNukeText('<img src="static/images/imagem_central-baixo.jpg"/>'));
//
//        $url = new XmlnukeManageUrl(URLTYPE::MODULE, $this->_module);
//        $url->addParam('action', ModuleActionLogin::LOGIN);
//        $url->addParam('ReturnUrl', $this->_urlReturn);
//
//        $form = new XmlFormCollection($this->_context, $url->getUrl() , $myWords->Value("LOGINTITLE"));
//        $form->setJSValidate(true);
//        $paragraph->addXmlnukeObject($form);
//
//        $textbox = new XmlInputTextBox('Login', 'loguser', $this->_context->ContextValue("loguser"), 16);
//        $textbox->setInputTextBoxType(InputTextBoxType::TEXT );
//        $textbox->setRequired(true);
//        $form->addXmlnukeObject($textbox);
//
//        $textbox = new XmlInputTextBox('Senha', 'password', '', 16);
//        $textbox->setInputTextBoxType(InputTextBoxType::PASSWORD );
//        $textbox->setRequired(true);
//        $form->addXmlnukeObject($textbox);
//
//        $button = new XmlInputButtons();
//        $button->addSubmit("Acessar", 'submit_button_login');
//        $form->addXmlnukeObject($button);
//
//        $url = new XmlnukeManageUrl(URLTYPE::MODULE, "login");
//        $url->addParam('action', ModuleActionLogin::FORGOTPASSWORD);
//        $url->addParam('ReturnUrl', $this->_urlReturn);
//
//        $label = new XmlInputLabelObjects('<div id="ajuda"></div>');
//        $link = new XmlAnchorCollection($url->getUrl(), null);
//        $link->addXmlnukeObject(new XmlnukeText('<div id="link_ajuda">Esqueci a minha senha</div>'));
//        $label->addXmlnukeObject($link);
//
//        $url = new XmlnukeManageUrl(URLTYPE::MODULE, "login");
//        $url->addParam('action', ModuleActionLogin::NEWUSER);
//        $url->addParam('ReturnUrl', $this->_urlReturn);
//
//        $link = new XmlAnchorCollection($url->getUrl(), null);
//        $link->addXmlnukeObject(new XmlnukeText('<div id="link_ajuda">Criar Usuário</div>'));
//        $label->addXmlnukeObject($link);
//        $form->addXmlnukeObject($label);
//
//    }
//
//    /**
//     * Make Login
//     *
//     */
//    protected function MakeLogin()
//    {
//            $myWords = $this->WordCollection();
//
//            $user = $this->_users->validateUserName($this->_context->ContextValue("loguser"), $this->_context->ContextValue("password"));
//
//            if ($user == null)
//            {
//                    $container = new XmlnukeUIAlert($this->_context, UIAlert::BoxAlert);
//                    $container->setAutoHide(5000);
//                    $container->addXmlnukeObject(new XmlnukeText($myWords->Value("LOGINFAIL"), true));
//                    $this->_blockCenter->addXmlnukeObject($container);
//                    $this->FormLogin();
//            }
//            else
//            {
//                    $this->_context->removeCookie("id_tema_panteon_definido");
//                    $this->_context->removeCookie("nome_tema_panteon_definido");
//
//                    $this->updateInfo($user->getField($this->_users->_UserTable->Username), $user->getField($this->_users->_UserTable->Id));
//
//            }
//
//    }
//
//    /**
//     * Forgot Password
//     *
//     */
//    protected function ForgotPassword()
//    {
//            $myWords = $this->WordCollection();
//
//            $paragraph = new XmlParagraphCollection();
//            $this->_blockCenter->addXmlnukeObject($paragraph);
//            $this->_blockCenter->addXmlnukeObject(new XmlNukeText('<img src="static/images/imagem_central-baixo.jpg"/>'));
//
//            $url = new XmlnukeManageUrl(URLTYPE::MODULE, $this->_module);
//            $url->addParam('action', ModuleActionLogin::FORGOTPASSWORDCONFIRM);
//            $url->addParam('ReturnUrl', $this->_urlReturn);
//
//            $form = new XmlFormCollection($this->_context, $url->getUrl() , $myWords->Value("FORGOTPASSTITLE"));
//            $paragraph->addXmlnukeObject($form);
//
//            $textbox = new XmlInputTextBox($myWords->Value("LABEL_EMAIL"), 'email', $this->_context->ContextValue("email"), 40);
//            $textbox->setInputTextBoxType(InputTextBoxType::TEXT );
//            $textbox->setDataType(INPUTTYPE::EMAIL);
//            $textbox->setRequired(true);
//            $form->addXmlnukeObject($textbox);
//
//            $button = new XmlInputButtons();
//            $button->addSubmit($myWords->Value("FORGOTPASSBUTTON"), 'submit_button');
//            $form->addXmlnukeObject($button);
//    }
//
//    /**
//     * Forgot Password Confirm
//     *
//     */
//    protected function ForgotPasswordConfirm()
//    {
//            $myWords = $this->WordCollection();
//
//            $container = new XmlnukeUIAlert($this->_context, UIAlert::BoxInfo);
//            $container->setAutoHide(5000);
//            $this->_blockCenter->addXmlnukeObject($container);
//
//            $user = $this->_users->getUserEMail( $this->_context->ContextValue("email") );
//
//            if (is_null($user))
//            {
//                    $container->addXmlnukeObject(new XmlnukeText($myWords->Value("FORGOTUSERFAIL"), true));
//                    $this->ForgotPassword();
//            }
//            else
//            {
//                    $newpassword = $this->getRandomPassword();
//                    $user->setField($this->_users->_UserTable->Password, $this->_users->getSHAPassword($newpassword));
//                    $this->sendWelcomeMessage($myWords, $user->getField($this->_users->_UserTable->Name), $user->getField($this->_users->_UserTable->Username), $user->getField($this->_users->_UserTable->Email), $newpassword );
//                    $this->_users->Save();
//                    $container->addXmlnukeObject(new XmlnukeText($myWords->Value("FORGOTUSEROK"), true));
//                    $this->FormLogin();
//            }
//    }
//
//    /**
//     * Create New User
//     * Modificado Panteon Escolar
//     */
//    protected function CreateNewUser()
//    {
//            $db = new InstituicaoDB($this->_context);
//            $it = $db->obterTodos();
//            $instituicoes = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_instituicao", "nome_instituicao");
//
//            $myWords = $this->WordCollection();
//
//            $paragraph = new XmlParagraphCollection();
//            $this->_blockCenter->addXmlnukeObject($paragraph);
//
//            $this->_blockCenter->addXmlnukeObject(new XmlNukeText('<img src="static/images/imagem_central-baixo.jpg"/>'));
//
//            $url = new XmlnukeManageUrl(URLTYPE::MODULE, $this->_module);
//            $url->addParam('action', ModuleActionLogin::NEWUSERCONFIRM);
//            $url->addParam('ReturnUrl', $this->_urlReturn);
//
//            $form = new XmlFormCollection($this->_context, $url->getUrl() , $myWords->Value("CREATEUSERTITLE"));
//            $paragraph->addXmlnukeObject($form);
//
//            $textbox = new XmlInputTextBox("Login", 'loguser', $this->_context->ContextValue("loguser"), 16);
//            $textbox->setInputTextBoxType(InputTextBoxType::TEXT );
//            $textbox->setDataType(INPUTTYPE::TEXT);
//            $textbox->setRequired(true);
//            $form->addXmlnukeObject($textbox);
//
//            $textbox = new XmlInputTextBox("Nome Completo", 'name', $this->_context->ContextValue("name"), 36);
//            $textbox->setInputTextBoxType(InputTextBoxType::TEXT );
//            $textbox->setDataType(INPUTTYPE::TEXT);
//            $textbox->setRequired(true);
//            $form->addXmlnukeObject($textbox);
//
//            $textbox = new XmlInputTextBox("E-Mail", 'email', $this->_context->ContextValue("email"), 32);
//            $textbox->setInputTextBoxType(InputTextBoxType::TEXT );
//            $textbox->setDataType(INPUTTYPE::EMAIL);
//            $textbox->setRequired(true);
//            $form->addXmlnukeObject($textbox);
//
//            $textbox = new XmlInputTextBox("Senha", 'senha_usuario', $this->_context->ContextValue("senha_usuario"), 12);
//            $textbox->setInputTextBoxType(InputTextBoxType::PASSWORD );
//            $textbox->setRequired(true);
//            $textbox->setMaxLength(12);
//            $form->addXmlnukeObject($textbox);
//
//            $textbox = new XmlInputTextBox("Confirmar Senha", 'confirmar_senha_usuario', $this->_context->ContextValue("confirmar_senha_usuario"), 12);
//            $textbox->setInputTextBoxType(InputTextBoxType::PASSWORD );
//            $textbox->setRequired(true);
//            $textbox->setMaxLength(12);
//            $form->addXmlnukeObject($textbox);
//
//            $textbox = new XmlEasyList(EasyListType::SELECTLIST, "id_instituicao", "Instituição", $instituicoes);
//            $textbox->setRequired(true);
//            $form->addXmlnukeObject($textbox);
//
//
//            $form->addXmlnukeObject(new XmlInputImageValidate('Verificação'));
//
//            if($this->_context->ContextValue("xsl") == "login")
//              $termo = new XmlInputCheck('Concordo com o <a href="termodeuso&amp;site=PanteonEscolar&amp;xsl=login&amp;lang=pt-br">Termo de Uso?</a>', 'termo_uso_usuario', '1');
//            else
//              $termo = new XmlInputCheck('Concordo com o <a class="lista_direita_detalhe" href="termodeuso&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br">Termo de Uso?</a>', 'termo_uso_usuario', '1');
//
//            $form->addXmlnukeObject($termo);
//
//            $button = new XmlInputButtons();
//            $button->addSubmit($myWords->Value("CREATEUSERBUTTON"), 'submit_button');
//            $form->addXmlnukeObject($button);
//    }
//
//    /**
//     * Confirm New user
//     *
//     */
//    protected function CreateNewUserConfirm()
//    {
//
//            $myWords = $this->WordCollection();
//            $container = new XmlnukeUIAlert($this->_context, UIAlert::BoxAlert);
//            $container->setAutoHide(5000);
//            $this->_blockCenter->addXmlnukeObject($container);
//            // $newpassword = $this->getRandomPassword();
//
//            $newpassword = $this->_context->ContextValue("senha_usuario");
//
//            if (!XmlInputImageValidate::validateText($this->_context))
//            {
//                    $container->addXmlnukeObject(new XmlnukeText($myWords->Value("OBJECTIMAGEINVALID"), true));
//                    $this->CreateNewUser();
//            }
//            else
//            {
//                    if ( $newpassword != $this->_context->ContextValue("confirmar_senha_usuario"))
//                    {
//                            $container->addXmlnukeObject(new XmlnukeText($myWords->Value("PASSFAIL"), true));
//                            $this->CreateNewUser($block);
//                    } else{
//                        if (!$this->_users->addUserPanteonEscolar( $this->_context->ContextValue("name"), $this->_context->ContextValue("loguser"), $this->_context->ContextValue("email"), $newpassword, $this->_context->ContextValue("id_instituicao") ) )
//                        {
//                                $container->addXmlnukeObject(new XmlnukeText($myWords->Value("CREATEUSERFAIL"), true));
//                                $this->CreateNewUser($block);
//                        }
//                        else
//                        {
//                                // $this->sendWelcomeMessage($myWords, $this->_context->ContextValue("name"), $this->_context->ContextValue("loguser"), $this->_context->ContextValue("email"), $newpassword );
//                                $this->_users->Save();
//                                $container->addXmlnukeObject(new XmlnukeText("Usuário criado com Sucesso!", true));
//                                $container->setUIAlertType(UIAlert::BoxInfo);
//                                $this->FormLogin($block);
//                        }
//                    }
//            }
//    }

  public function __constructor() {}
  public function __destruct() { }
  public function useCache()
  {
    return false;
  }

  public function requiresAuthentication()
  {
    return false;
  }

}

?>
