<?php
/*
*=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
*  Copyright:
*
*  XMLNuke: A Web Development Framework based on XML.
*
*  Main Specification: Joao Gilberto Magalhaes, joao at byjg dot com
*
*  This file is part of XMLNuke project. Visit http://www.xmlnuke.com
*  for more information.
*
*  This program is free software; you can redistribute it and/or
*  modify it under the terms of the GNU General Public License
*  as published by the Free Software Foundation; either version 2
*  of the License, or (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program; if not, write to the Free Software
*  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*
*=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
*/

require_once("module.loginbase.class.php");

ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseModule.class.php");

class ModuleActionLogin extends ModuleAction 
{
	const LOGIN = 'action.LOGIN';
	const NEWUSER = 'action.NEWUSER';
	const NEWUSERCONFIRM = 'action.NEWUSERCONFIRM';
	const FORGOTPASSWORD = 'action.FORGOTPASSWORD';
	const FORGOTPASSWORDCONFIRM = 'action.FORGOTPASSWORDCONFIRM';
}

/**
 * Login is a default module descendant from BaseModule class.
 * This class shows/edit the profile from the current user.
 * 
 * @see module.IModule.class.php
 * @see module.BaseModule.class.php
 * @subpackage xmlnuke.modules
 */
class Login extends LoginBase
{
	/**
	 * Users
	 *
	 * @var UsersAnyDataSet
	 */
	protected $_users;
		
	/**
	 * Module
	 *
	 * @var String
	 */
	private  $_module = "login";
	
	/**
	 * BlockCenter
	 *
	 * @var XmlBlockCollection
	 */
	protected $_blockCenter;
	
	/**
	 * Default constructor
	 *
	 * @return Login
	 */
	public function Login()
	{}

	/**
	 * Create Page
	 *
	 * @return PageXml
	 */
	public function CreatePage()
	{
		$myWords = $this->WordCollection();

                if($this->_context->IsAuthenticated()) $this->_context->redirectUrl("/meuperfil");

		$this->_users = $this->getUsersDatabase();
		
		$this->defaultXmlnukeDocument->setPageTitle($myWords->Value("TITLELOGIN"));
		$this->defaultXmlnukeDocument->setAbstract($myWords->Value("TITLELOGIN"));
		
		$this->_blockCenter = new XmlBlockCollection( $myWords->Value("TITLELOGIN"), BlockPosition::Center );

                $blockHead   = new XmlBlockCollection(NULL, BlockPosition::Right);
                $blockInfo   = new XmlBlockCollection(NULL, BlockPosition::Left);

                $this->defaultXmlnukeDocument->addXmlnukeObject($blockHead);
                $blockHead->addXmlnukeObject(new LoginDBXML($this->_context, "menuHead"));

                $this->defaultXmlnukeDocument->addXmlnukeObject($blockInfo);
                $blockInfo->addXmlnukeObject(new LoginDBXML($this->_context, "listarDireita"));

		$this->defaultXmlnukeDocument->addXmlnukeObject($this->_blockCenter);
		
		$this->_urlReturn = $this->_context->ContextValue("ReturnUrl");

		
		switch ($this->_action) 
		{
			case ModuleActionLogin::LOGIN :
				$this->MakeLogin();
				break;
			case ModuleActionLogin::FORGOTPASSWORD :
				$this->ForgotPassword();
				break;
			case ModuleActionLogin::FORGOTPASSWORDCONFIRM :
				$this->ForgotPasswordConfirm();
				break;
			case ModuleActionLogin::NEWUSER :
				$this->CreateNewUser();
				break;
			case ModuleActionLogin::NEWUSERCONFIRM :
				$this->CreateNewUserConfirm();
				break;
			default:
				$this->FormLogin();
				break;
		}
		return $this->defaultXmlnukeDocument->generatePage();
	}
	
	/**
	 * Make Login
	 *
	 */
	protected function MakeLogin()
	{
		$myWords = $this->WordCollection();

		$user = $this->_users->validateUserName($this->_context->ContextValue("loguser"), $this->_context->ContextValue("password"));
                
		if ($user == null)
		{
			$container = new XmlnukeUIAlert($this->_context, UIAlert::BoxAlert);
			$container->setAutoHide(5000);
			$container->addXmlnukeObject(new XmlnukeText($myWords->Value("LOGINFAIL"), true));
			$this->_blockCenter->addXmlnukeObject($container);
			$this->FormLogin();
		}
		else
		{
                        $this->_context->removeCookie("id_tema_panteon_definido");
                        $this->_context->removeCookie("nome_tema_panteon_definido");

			$this->updateInfo($user->getField($this->_users->_UserTable->Username), $user->getField($this->_users->_UserTable->Id));

                }
                        
	}

	/**
	 * Form Login
	 *
	 */
	protected function FormLogin()
	{
		$myWords = $this->WordCollection();

		$paragraph = new XmlParagraphCollection();
		$this->_blockCenter->addXmlnukeObject($paragraph);

                $this->_blockCenter->addXmlnukeObject(new XmlNukeText('<img src="static/images/imagem_central-baixo.jpg"/>'));

                $url = new XmlnukeManageUrl(URLTYPE::MODULE, $this->_module);
		$url->addParam('action', ModuleActionLogin::LOGIN);
		$url->addParam('ReturnUrl', $this->_urlReturn);
		
		$form = new XmlFormCollection($this->_context, $url->getUrl() , $myWords->Value("LOGINTITLE"));
		$form->setJSValidate(true);
		$paragraph->addXmlnukeObject($form);
		
		$textbox = new XmlInputTextBox('Login', 'loguser', $this->_context->ContextValue("loguser"), 16);
		$textbox->setInputTextBoxType(InputTextBoxType::TEXT );
		$textbox->setRequired(true);
		$form->addXmlnukeObject($textbox);
		
		$textbox = new XmlInputTextBox('Senha', 'password', '', 10);
		$textbox->setInputTextBoxType(InputTextBoxType::PASSWORD );
		$textbox->setRequired(true);
		$form->addXmlnukeObject($textbox);

		$button = new XmlInputButtons();
		$button->addSubmit("Acessar Panteon Escolar", 'submit_button_login');
		$form->addXmlnukeObject($button);
		
		$url = new XmlnukeManageUrl(URLTYPE::MODULE, $this->_module);
		$url->addParam('action', ModuleActionLogin::FORGOTPASSWORD);
		$url->addParam('ReturnUrl', $this->_urlReturn);
		
		$label = new XmlInputLabelObjects('<div id="ajuda">'.$myWords->Value("LOGINPROBLEMSMESSAGE").'</div>');
		$link = new XmlAnchorCollection($url->getUrl(), null);
		$link->addXmlnukeObject(new XmlnukeText('<div id="link_ajuda">Esqueci a minha senha</div>'));
		$label->addXmlnukeObject($link);
		
		$url = new XmlnukeManageUrl(URLTYPE::MODULE, $this->_module);
		$url->addParam('action', ModuleActionLogin::NEWUSER);
		$url->addParam('ReturnUrl', $this->_urlReturn);
		
		$link = new XmlAnchorCollection($url->getUrl(), null);
		$link->addXmlnukeObject(new XmlnukeText('<div id="link_ajuda">Criar Usuário</div>'));
		$label->addXmlnukeObject($link);
		$form->addXmlnukeObject($label);


	}

	/**
	 * Forgot Password
	 *
	 */
	protected function ForgotPassword()
	{
		$myWords = $this->WordCollection();
		
		$paragraph = new XmlParagraphCollection();
		$this->_blockCenter->addXmlnukeObject($paragraph);
                $this->_blockCenter->addXmlnukeObject(new XmlNukeText('<img src="static/images/imagem_central-baixo.jpg"/>'));
		
		$url = new XmlnukeManageUrl(URLTYPE::MODULE, $this->_module);
		$url->addParam('action', ModuleActionLogin::FORGOTPASSWORDCONFIRM);
		$url->addParam('ReturnUrl', $this->_urlReturn);
		
		$form = new XmlFormCollection($this->_context, $url->getUrl() , $myWords->Value("FORGOTPASSTITLE"));
		$paragraph->addXmlnukeObject($form);
		
		$textbox = new XmlInputTextBox($myWords->Value("LABEL_EMAIL"), 'email', $this->_context->ContextValue("email"), 40);
		$textbox->setInputTextBoxType(InputTextBoxType::TEXT );
		$textbox->setDataType(INPUTTYPE::EMAIL);
		$textbox->setRequired(true);
		$form->addXmlnukeObject($textbox);
		
		$button = new XmlInputButtons();
		$button->addSubmit($myWords->Value("FORGOTPASSBUTTON"), 'submit_button');
		$form->addXmlnukeObject($button);
	}
	
	/**
	 * Forgot Password Confirm
	 *
	 */
	protected function ForgotPasswordConfirm()
	{
		$myWords = $this->WordCollection();
		
		$container = new XmlnukeUIAlert($this->_context, UIAlert::BoxInfo);
		$container->setAutoHide(5000);
		$this->_blockCenter->addXmlnukeObject($container);
		
		$user = $this->_users->getUserEMail( $this->_context->ContextValue("email") );
		
		if (is_null($user))
		{
			$container->addXmlnukeObject(new XmlnukeText($myWords->Value("FORGOTUSERFAIL"), true));			
			$this->ForgotPassword();
		}
		else
		{
			$newpassword = $this->getRandomPassword();
			$user->setField($this->_users->_UserTable->Password, $this->_users->getSHAPassword($newpassword));
			$this->sendWelcomeMessage($myWords, $user->getField($this->_users->_UserTable->Name), $user->getField($this->_users->_UserTable->Username), $user->getField($this->_users->_UserTable->Email), $newpassword );
			$this->_users->Save();
			$container->addXmlnukeObject(new XmlnukeText($myWords->Value("FORGOTUSEROK"), true));
			$this->FormLogin();
		}
	}

	/**
	 * Create New User
	 * Modificado Panteon Escolar
	 */
	protected function CreateNewUser()
	{
                $db = new InstituicaoDB($this->_context);
                $it = $db->obterTodos();
                $instituicoes = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_instituicao", "nome_instituicao");

		$myWords = $this->WordCollection();
		
		$paragraph = new XmlParagraphCollection();
		$this->_blockCenter->addXmlnukeObject($paragraph);
                
        $this->_blockCenter->addXmlnukeObject(new XmlNukeText('<img src="static/images/imagem_central-baixo.jpg"/>'));
		
		$url = new XmlnukeManageUrl(URLTYPE::MODULE, $this->_module);
		$url->addParam('action', ModuleActionLogin::NEWUSERCONFIRM);
		$url->addParam('ReturnUrl', $this->_urlReturn);
		
		$form = new XmlFormCollection($this->_context, $url->getUrl() , $myWords->Value("CREATEUSERTITLE"));
		$paragraph->addXmlnukeObject($form);
		
		$textbox = new XmlInputTextBox("Login", 'loguser', $this->_context->ContextValue("loguser"), 16);
		$textbox->setInputTextBoxType(InputTextBoxType::TEXT );
		$textbox->setDataType(INPUTTYPE::TEXT);
		$textbox->setRequired(true);
		$form->addXmlnukeObject($textbox);
		
		$textbox = new XmlInputTextBox("Nome Completo", 'name', $this->_context->ContextValue("name"), 36);
		$textbox->setInputTextBoxType(InputTextBoxType::TEXT );
		$textbox->setDataType(INPUTTYPE::TEXT);
		$textbox->setRequired(true);
		$form->addXmlnukeObject($textbox);
		
		$textbox = new XmlInputTextBox("E-Mail", 'email', $this->_context->ContextValue("email"), 32);
		$textbox->setInputTextBoxType(InputTextBoxType::TEXT );
		$textbox->setDataType(INPUTTYPE::EMAIL);
		$textbox->setRequired(true);
		$form->addXmlnukeObject($textbox);

		$textbox = new XmlInputTextBox("Senha", 'senha_usuario', $this->_context->ContextValue("senha_usuario"), 12);
		$textbox->setInputTextBoxType(InputTextBoxType::PASSWORD );
		$textbox->setRequired(true);
                $textbox->setMaxLength(12);
		$form->addXmlnukeObject($textbox);

		$textbox = new XmlEasyList(EasyListType::SELECTLIST, "id_instituicao", "Instituição", $instituicoes);
		$textbox->setRequired(true);
		$form->addXmlnukeObject($textbox);


		$form->addXmlnukeObject(new XmlInputImageValidate('Verificação'));

                if($this->_context->ContextValue("xsl") == "login")
                  $termo = new XmlInputCheck('Concordo com o <a href="termodeuso&amp;site=PanteonEscolar&amp;xsl=login&amp;lang=pt-br">Termo de Uso?</a>', 'termo_uso_usuario', '1');
                else
                  $termo = new XmlInputCheck('Concordo com o <a class="lista_direita_detalhe" href="termodeuso&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br">Termo de Uso?</a>', 'termo_uso_usuario', '1');

                $form->addXmlnukeObject($termo);

		$button = new XmlInputButtons();
		$button->addSubmit($myWords->Value("CREATEUSERBUTTON"), 'submit_button');
		$form->addXmlnukeObject($button);
	}

	/**
	 * Confirm New user
	 *
	 */
	protected function CreateNewUserConfirm()
	{
		$myWords = $this->WordCollection();
		$container = new XmlnukeUIAlert($this->_context, UIAlert::BoxAlert);
		$container->setAutoHide(5000);
		$this->_blockCenter->addXmlnukeObject($container);
		// $newpassword = $this->getRandomPassword();

                $newpassword = $this->_context->ContextValue("senha_usuario");
		
		if (!XmlInputImageValidate::validateText($this->_context))
		{
			$container->addXmlnukeObject(new XmlnukeText($myWords->Value("OBJECTIMAGEINVALID"), true));
			$this->CreateNewUser();
		}
		else 
		{
			if (!$this->_users->addUserPanteonEscolar( $this->_context->ContextValue("name"), $this->_context->ContextValue("loguser"), $this->_context->ContextValue("email"), $newpassword, $this->_context->ContextValue("id_instituicao") ) )
			{
				$container->addXmlnukeObject(new XmlnukeText($myWords->Value("CREATEUSERFAIL"), true));
				$this->CreateNewUser($block);
			}
			else
			{
				// $this->sendWelcomeMessage($myWords, $this->_context->ContextValue("name"), $this->_context->ContextValue("loguser"), $this->_context->ContextValue("email"), $newpassword );
				$this->_users->Save();
				$container->addXmlnukeObject(new XmlnukeText("Usuário criado com Sucesso!", true));
				$container->setUIAlertType(UIAlert::BoxInfo);
				$this->FormLogin($block);
			}
		}
	}	
}
?>
