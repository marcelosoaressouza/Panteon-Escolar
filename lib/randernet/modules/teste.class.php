<?php
//ModuleFactory::IncludePhp("sgem", "classes/base/sgembasemodule.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseModule.class.php");
class Teste extends PanteonEscolarBaseModule
{
  /**
  *@desc Default constructor
  */
  public function Teste()
  {}

  /**
  *@return bool
  *@desc requiresAuthentication IModule interface.
  */
  public function requiresAuthentication()
  {
    return false;
  }

  /**
   * Return the LanguageCollection used in this module
   *
   * @return LanguageCollection
   */
  public function WordCollection()
  {
    $myWords = parent::WordCollection();

    if(!$myWords->loadedFromFile())
    {
      // English Words
      $myWords->addText("en-us", "TITLE", "Module Requested Not Found");
      $myWords->addText("en-us", "MESSAGE", "The requested module {0}");
      // Portuguese Words
      $myWords->addText("pt-br", "TITLE", "Módulo solicitado não foi encontrado");
      $myWords->addText("pt-br", "MESSAGE", "O módulo solicitado {0}");
    }

    return $myWords;
  }

  /**
   * Returns if use cache
   *
   * @return bool
   */
  public function useCache()
  {
    return false;
  }

  /**
   * Output error message
   *
   * @return PageXml
   */
  public function CreatePage()
  {
    $myWords = $this->WordCollection();
    $xmlnukeDoc = new XmlnukeDocument("", "");


    $block = new XmlBlockCollection("", BlockPosition::Center);
    $xmlnukeDoc->addXmlnukeObject($block);

    $fromEmail = MailUtil::getEmailFromID($this->_context, "PANTEON");
    $toEmail = MailUtil::getEmailFromID($this->_context, "RASMANGABEIRA");
    $cc = MailUtil::getEmailFromID($this->_context, "RANDER");
    $bcc = MailUtil::getEmailFromID($this->_context, "RANDERNET");

    $subject = "Teste de envio";
    $body = "Teste de envio";

    MailUtil::Mail($this->_context, $fromEmail, $toEmail, $subject, $cc, $bcc, $body);



    // $x =  new xmlnuke


//                $treeview = new XmlnukeTreeview($this->_context, "Treeview!!!");
//                $block->addXmlnukeObject($treeview);
//
//                $treeviewFolder = new XmlnukeTreeViewFolder($this->_context, "Folder", "static/images/icones/icone_verdetalhe.png", "folder1");
//                $treeview->addChild($treeviewFolder);
//                $treeviewFolder->setAction(TreeViewActionType::OpenUrlInsideContainer, "module:randernet.teste", $actionContainer);
//
//                $treeviewLeaf = new XmlnukeTreeViewLeaf($this->_context, "Leaf", "static/images/icones/icone_remover.png", "leaf1");
//                $treeviewFolder->addChild($treeviewLeaf);

    //$treeviewLeaf->setAction($actionType, $actionText, $actionContainer);

    // ########  Teste combo via AJAX  ###################

//                $nacionalidade_ui_edit = new NacionalidadeUIEdit($this->_context, $this->_moduleName, $this->_words);

//                if($this->_context->ContextValue("acao")=="combo_teste"){
//                    $block->addXmlnukeObject($nacionalidade_ui_edit->criarRanderNetEasyListAjaxRequestNacionalidade2(RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX_REQUEST,4));
//                }else{
//                    $block->addXmlnukeObject($nacionalidade_ui_edit->criarRanderNetEasyListAjaxRequestNacionalidade(RanderNetEasyListType::SELECTLIST));
//                    $block->addXmlnukeObject($nacionalidade_ui_edit->criarRanderNetEasyListAjaxRequestNacionalidade2(RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX,1));
//                }

    // ######## FIM Teste combo via AJAX  ###################

    // ########  Teste DUALLIST ASMSelect  ###################
//                $form = new XmlFormCollection($this->_context, "", "");
//                $nacionalidade_ui_edit = new NacionalidadeUIEdit($this->_context, $this->_moduleName, $this->_words);
//                $form->addXmlnukeObject($nacionalidade_ui_edit->criarDualListNacionalidade());
//                $form->addXmlnukeObject($nacionalidade_ui_edit->criarDualListASMSelectNacionalidade());
//                $form->addXmlnukeObject($nacionalidade_ui_edit->criarDualListASMSelectNacionalidade2());
//                //$asmselect = new RanderNetXmlDualListASMSelect($this->_context, "asmselect", $captionLeft, $captionRight);
//                 $block->addXmlnukeObject($form);
//                if($this->isPostBack()){
//                    Debug::PrintValue($_REQUEST);
//                }
//
//                $button = new XmlInputButtons();
//                $button->addSubmit("enviar");
//                $form->addXmlnukeObject($button);

    // ########  Teste DUALLIST ASMSelect  ###################



    // ########  Teste CloneField  ###################
//                $div_clone_field = new RanderNetXmlCloneFieldContainerCollection("clone_field", "Adicionar CloneField!");
//                //$div_clone_field->setStyle("display:none;");
//                $div_clone_field->addXmlnukeObject(new XmlInputTextBox("teste", "txt[]", "", 20));
//                $div_clone_field->addXmlnukeObject(new XmlInputTextBox("teste2", "txt2[]", "", 20));
//                $div_clone_field->addXmlnukeObject(new XmlInputTextBox("teste3", "txt3[]", "", 20));
//                $block->addXmlnukeObject($div_clone_field);
    // ######## FIM Teste CloneField  ###################


    return $xmlnukeDoc->generatePage();

  }

}

?>
