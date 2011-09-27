<?php

class PanteonEscolarXmlInputObjectType extends XmlInputObjectType
{
  const RANDERNET_DATE = 14;
  const RANDERNET_DATETIME = 15;
  const RANDERNET_DUALLIST_ASMSELECT = 16;
}


/**
 *
 */
class PanteonEscolarProcessPageState extends ProcessPageStateDB
{

  /**
   *
   * @param <type> $context
   * @param <type> $fields
   * @param <type> $header
   * @param <type> $module
   * @param <type> $buttons
   * @param <type> $table
   */
  public function __construct($context, $fields, $header, $module, $buttons, $table)
  {
    parent::__construct($context, $fields, $header, $module, $buttons, $table, PanteonEscolarBaseDBAccess::DATABASE());

    if($this->_currentAction == ProcessPageStateBase::ACTION_DELETE)
    {
      $this->_currentAction = ProcessPageStateBase::ACTION_DELETE_CONFIRM;
    }
  }

  /**
   * @desc
   * @param
   * @return IXmlnukeDocumentObject
   */
  protected function listAllRecords()
  {
//    XmlEditList $editList
    $editList = new XmlEditList($this->_context, $this->_header, $this->_module, false, false, false, false);
    $editList->setDataSource($this->getAllRecords());
    $editList->setPageSize($this->_qtdRows, $this->_curPage);
    $editList->setEnablePage(true);
    //$editList->addParameter("filter", $this->_filter);
    //$editList->addParameter("sort", $this->_sort);
    foreach($this->_parameter as $key => $value)
    {
      $editList->addParameter($key, $value);
    }

    if($this->_new)
    {
      $cb = new CustomButtons();
      $cb->action = self::ACTION_NEW;
      $cb->alternateText = $this->_lang->Value("TXT_NEW");
      $cb->icon = "common/editlist/ic_novo.gif";
      $cb->enabled = true;
      $cb->multiple = MultipleSelectType::NONE;
      $editList->setCustomButton($cb);
    }

    if($this->_view)
    {
      $cb = new CustomButtons();
      $cb->action = self::ACTION_VIEW;
      $cb->alternateText = $this->_lang->Value("TXT_VIEW");
      $cb->icon = "common/editlist/ic_detalhes.gif";
      $cb->enabled = true;
      $cb->multiple = MultipleSelectType::ONLYONE;
      $editList->setCustomButton($cb);
    }

    if($this->_edit)
    {
      $cb = new CustomButtons();
      $cb->action = self::ACTION_EDIT;
      $cb->alternateText = $this->_lang->Value("TXT_EDIT");
      $cb->icon = "common/editlist/ic_editar.gif";
      $cb->enabled = true;
      $cb->multiple = MultipleSelectType::ONLYONE;
      $editList->setCustomButton($cb);
    }

    if($this->_delete)
    {
      $cb = new CustomButtons();
      $cb->action = self::ACTION_DELETE;
      $cb->alternateText = $this->_lang->Value("TXT_DELETE");
      $cb->icon = "common/editlist/ic_excluir.gif";
      $cb->enabled = true;
      $cb->multiple = MultipleSelectType::MULTIPLE;
      $editList->setCustomButton($cb);
    }

    if($this->_buttons != null)
    {
      for($i = 0, $buttonsLength = sizeof($this->_buttons); $i < $buttonsLength; $i++)
      {
//        CustomButtons $cb;
        if(($this->_buttons[$i]->action != "") || ($this->_buttons[$i]->url != ""))
        {
          $cb = new CustomButtons();
          $cb->action = $this->_buttons[$i]->action;
          $cb->alternateText = $this->_buttons[$i]->alternateText;
          $cb->icon = $this->_buttons[$i]->icon;
          $cb->url = $this->_buttons[$i]->url;
          $cb->enabled = true;
          $cb->multiple = $this->_buttons[$i]->multiple;
          $editList->setCustomButton($cb);
        }
      }
    }


    $fldKey = "";

    for($i = 0, $current = 0; $i < sizeof($this->_keyIndex); $i++, $current++)
    {
      $fldKey .= (($fldKey != "") ? "|" : "") . $this->_fields[$this->_keyIndex[$i]]->fieldName;
    }

    $field = new EditListField();
    $field->fieldData = $fldKey;
    $field->editlistName = "#";
    $field->formatter = new ProcessPageStateBaseFormatterKey();
    $field->fieldType = EditListFieldType::FORMATTER;
    $editList->addEditListField($field);

    for($i = 0, $fieldLength = sizeof($this->_fields); $i < $fieldLength; $i++, $current++)
    {
      if($this->_fields[$i]->visibleInList)
      {
        $field = new EditListField();
        $field->fieldData = $this->_fields[$i]->fieldName;
        $field->editlistName = $this->_fields[$i]->fieldCaption;

        if($this->_fields[$i]->fieldXmlInput == XmlInputObjectType::SELECTLIST)
        {
          $field->fieldType = EditListFieldType::LOOKUP;
          $field->arrayLookup = $this->_fields[$i]->arraySelectList;
        }

        elseif($this->_fields[$i]->fieldXmlInput == XmlInputObjectType::DUALLIST)
        {
          $field->fieldType = EditListFieldType::FORMATTER;
          $field->formatter = new ProcessPageStateBaseFormatterDualList($this->_fields[$i]->arraySelectList);
        }

        else
        {
          $field->fieldType = EditListFieldType::TEXT;
        }

        $field->newColumn = $this->_fields[$i]->newColumn;

        if(!is_null($this->_fields[$i]->editListFormatter))
        {
          $field->formatter = $this->_fields[$i]->editListFormatter;
          $field->fieldType = EditListFieldType::FORMATTER;
        }

        $field = $this->editListFieldCustomize($field, $this->_fields[$i]);

        $editList->addEditListField($field);
      }
    }

    return $editList;
  }

  /**
   *
   * @param array $param
   * @return string
   */
  protected function getWhereClause(&$param)
  {
    $arValueId = explode("|", $this->_valueId);
    $where = "";
    $i = 0;
    foreach($this->_keyIndex as $keyIndex)
    {
      $where .= (($where != "") ? " and " : "") . $this->_fields[$keyIndex]->fieldName . " IN( ";
      $arKeyIndex = explode(",", $arValueId[$i++]);
      $z = 0;
      foreach($arKeyIndex as $key)
      {
        $z++;
        $where .= " [[valueid" . $keyIndex . $z . "]],";
        $param["valueid" . $keyIndex . $z] = $key;
      }
      $where = substr($where, 0, strlen($where) - 1);
      $where .= " ) ";
    }
    return $where;
  }

  /**
   * @desc Contains specific instructions to generate all XML informations-> This method is processed only one time-> Usually is the last method processed->
   * @param DOMNode $current DOMNode where the XML will be created->
   * @return void
   */
  public function generateObject($current)
  {
    // Improve Security
    $wrongway = !$this->_edit && (($this->_currentAction == self::ACTION_EDIT) || ($this->_currentAction == self::ACTION_EDIT_CONFIRM));
    $wrongway = $wrongway || (!$this->_new && (($this->_currentAction == self::ACTION_NEW) || ($this->_currentAction == self::ACTION_NEW_CONFIRM)));
    $wrongway = $wrongway || (!$this->_delete && (($this->_currentAction == self::ACTION_DELETE) || ($this->_currentAction == self::ACTION_DELETE_CONFIRM)));

    if($wrongway)
    {
      $message = $this->_lang->Value("MSG_DONT_HAVEGRANT");
      $p = new XmlParagraphCollection();
      $p->addXmlnukeObject(new XmlnukeText($message, true, true, false));
      $p->generateObject($current);
      return;
    }

    // Checkings!
    if($this->_context->ContextValue(self::PARAM_CANCEL) != "")
    {
      $this->listAllRecords()->generateObject($current);
    }

    else if(strpos($this->_currentAction, "_confirm") !== false)
    {
      try
      {
        $validateResult = $this->updateRecord();
      }

      catch(Exception $ex)
      {
        $nvc = array("Erro ao tentar inserir ou atualizar registro!");

        //XmlParagraphCollection $p
        $p = new XmlParagraphCollection();
        $p->addXmlnukeObject(new XmlEasyList(EasyListType::UNORDEREDLIST, "Error", $this->_lang->Value("ERR_FOUND"), $nvc, ""));

        $uialerta = new XmlnukeUIAlert($this->_context, UIAlert::Dialog, "Detalhes do Erro!");
        $uialerta->setOpenAction(UIAlertOpenAction::Button, "Clique aqui para ver detalhes do erro!");
        //$uialerta->addRedirectButton("Clique aqui para abrir chamado!", "http://sgc/");

        $uialerta->addXmlnukeObject(new XmlnukeText($ex->getMessage()));
        $uialerta->setDimensions(400, 300);
        $p->addXmlnukeObject($uialerta);

        $p->addXmlnukeObject(new XmlnukeBreakLine());

        //XmlAnchorCollection $a
        $a = new XmlAnchorCollection("javascript:history.go(-1)", "");
        $a->addXmlnukeObject(new XmlnukeText($this->_lang->Value("TXT_GOBACK")));
        $p->addXmlnukeObject($a);
        $validateResult = $p;
      }

      if(is_null($validateResult))
      {
        $this->_context->redirectUrl($this->redirProcessPage(false));
      }

      else
      {
        $validateResult->generateObject($current);

        if($this->_currentAction != ProcessPageStateBase::ACTION_NEW_CONFIRM)
        {
          $this->showCurrentRecord()->generateObject($current);
        }
      }
    }

    else if($this->_currentAction == self::ACTION_MSG)
    {
      $this->showResultMessage()->generateObject($current);
      $this->listAllRecords()->generateObject($current);
    }

    else if(($this->_currentAction == self::ACTION_NEW) || ($this->_currentAction == self::ACTION_VIEW) || ($this->_currentAction == self::ACTION_EDIT) || ($this->_currentAction == self::ACTION_DELETE))
    {

      $this->showCurrentRecord()->generateObject($current);
    }

    else
    {
      $this->listAllRecords()->generateObject($current);
    }
  }



  /**
  *@desc
  *@param ProcessPageField $field
  *@param string $curValue
  *@return IXmlnukeDocumentObject
  */
  public function renderField($field, $curValue)
  {
    if(($field->fieldXmlInput == XmlInputObjectType::TEXTBOX) || ($field->fieldXmlInput == XmlInputObjectType::PASSWORD) || ($field->fieldXmlInput == XmlInputObjectType::TEXTBOX_AUTOCOMPLETE))
    {
//      XmlInputTextBox $itb
      $itb = new XmlInputTextBox($field->fieldCaption, $field->fieldName, $curValue, $field->size);
      $itb->setRequired($field->required);
      $itb->setRange($field->rangeMin, $field->rangeMax);
      $itb->setDescription($field->fieldCaption);

      if($field->fieldXmlInput == XmlInputObjectType::PASSWORD)
      {
        $itb->setInputTextBoxType(InputTextBoxType::PASSWORD);
      }

      elseif($field->fieldXmlInput == XmlInputObjectType::TEXTBOX_AUTOCOMPLETE)
      {
        if(!is_array($field->arraySelectList) || ($field->arraySelectList["URL"]=="") || ($field->arraySelectList["PARAMREQ"]==""))
        {
          throw new XMLNukeException(
            "You have to pass a array to arraySelectList field parameter with the following keys: URL, PARAMREQ. Optional: ATTRINFO, ATTRID, JSCALLBACK");
        }

        $itb->setInputTextBoxType(InputTextBoxType::TEXT);
        $itb->setAutosuggest($this->_context, $field->arraySelectList["URL"], $field->arraySelectList["PARAMREQ"], $field->arraySelectList["ATTRINFO"], $field->arraySelectList["ATTRID"], $field->arraySelectList["JSCALLBACK"]);
      }

      else
      {
        $itb->setInputTextBoxType(InputTextBoxType::TEXT);
      }

      $itb->setReadOnly($this->isReadOnly($field));
      $itb->setMaxLength($field->maxLength);
      $itb->setDataType($field->dataType);
      return $itb;
    }

    else if(($field->fieldXmlInput == XmlInputObjectType::RADIOBUTTON) || ($field->fieldXmlInput == XmlInputObjectType::CHECKBOX))
    {
//      XmlInputCheck $ic
      $ic = new XmlInputCheck($field->fieldCaption, $field->fieldName, $field->defaultValue);

      if($field->fieldXmlInput == XmlInputObjectType::TEXTBOX)
      {
        $ic->setType(InputCheckType::CHECKBOX);
      }

      else
      {
        $ic->setType(InputCheckType::CHECKBOX);
      }

      $ic->setChecked($field->defaultValue == $curValue);
      $ic->setReadOnly($this->isReadOnly($field));
      return $ic;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::MEMO)
    {
//      XmlInputMemo $im
      $im = new XmlInputMemo($field->fieldCaption, $field->fieldName, $curValue);
      $im->setWrap("SOFT");
      $im->setSize(50, 8);
      $im->setReadOnly($this->isReadOnly($field));
      return $im;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::HTMLTEXT)
    {
//      XmlInputMemo $im
      $im = new XmlInputMemo($field->fieldCaption, $field->fieldName, $curValue);
      //$im->setWrap("SOFT");
      //$im->setSize(50, 8);
      $im->setVisualEditor(true);
      $im->setReadOnly($this->isReadOnly($field));
      return $im;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::HIDDEN)
    {
//      XmlInputHidden $ih
      $ih = new XmlInputHidden($field->fieldName, $curValue);
      return $ih;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::SELECTLIST)
    {
//      XmlEasyList $el
      $el = new XmlEasyList(EasyListType::SELECTLIST, $field->fieldName, $field->fieldCaption, $field->arraySelectList, $curValue);
      $el->setReadOnly($this->isReadOnly($field));
      return $el;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::DUALLIST)
    {
      $ards = new ArrayDataSet($field->arraySelectList, "value");
      $duallist = new XmlDualList($this->_context, $field->fieldName, $this->_lang->Value("TXT_AVAILABLE", $field->fieldCaption), $this->_lang->Value("TXT_USED", $field->fieldCaption));
      $duallist->createDefaultButtons();
      $duallist->setDataSourceFieldName("key", "value");

      if($curValue != "")
      {
        $ardt = explode(",", $curValue);
        $ardt  = array_flip($ardt);
        foreach($ardt as $key=>$value)
        {
          $ardt[$key] = $field->arraySelectList[$key];
        }
      }

      else
      {
        $ardt = array();
      }

      $ards2 = new ArrayDataSet($ardt, "value");

      $duallist->setDataSource($ards->getIterator(), $ards2->getIterator());

      $label = new XmlInputLabelObjects("=>");
      $label->addXmlnukeObject($duallist);

      return $label;
    }

    else if($field->fieldXmlInput == PanteonEscolarXmlInputObjectType::RANDERNET_DUALLIST_ASMSELECT)
    {
      $ards = new ArrayDataSet($field->arraySelectList, "value");
      //$duallist = new XmlDualList($this->_context, $field->fieldName, $this->_lang->Value("TXT_AVAILABLE", $field->fieldCaption), $this->_lang->Value("TXT_USED", $field->fieldCaption));
      $duallist = new RanderNetXmlDualListASMSelect($this->_context, $field->fieldName, $this->_lang->Value("TXT_AVAILABLE", $field->fieldCaption));
      //$duallist->createDefaultButtons();
      $duallist->setDataSourceFieldName("key", "value");
      $duallist->setTitle("Selecione");

      if($curValue != "")
      {
        $ardt = explode(",", $curValue);
        $ardt  = array_flip($ardt);
        foreach($ardt as $key=>$value)
        {
          $ardt[$key] = $field->arraySelectList[$key];
        }
      }

      else
      {
        $ardt = array();
      }

      $ards2 = new ArrayDataSet($ardt, "value");

      $duallist->setDataSource($ards->getIterator(), $ards2->getIterator());

      $label = new XmlInputLabelObjects("");
      $label->addXmlnukeObject($duallist);

      return $label;
    }

    else if(($field->fieldXmlInput == XmlInputObjectType::DATE) || ($field->fieldXmlInput == XmlInputObjectType::DATETIME))
    {
      $cur = explode(" ", $curValue);
      $idt = new XmlInputDateTime($field->fieldCaption, $field->fieldName, $this->_dateFormat, ($field->fieldXmlInput == XmlInputObjectType::DATETIME), $cur[0], $cur[1]);
      return $idt;
    }

    else if(($field->fieldXmlInput == PanteonEscolarXmlInputObjectType::RANDERNET_DATE) || ($field->fieldXmlInput == PanteonEscolarXmlInputObjectType::RANDERNET_DATETIME))
    {
      $cur = explode(" ", $curValue);
      $idt = new RanderNetXmlInputDateTime($field->fieldCaption, $field->fieldName, $this->_dateFormat, ($field->fieldXmlInput == XmlInputObjectType::DATETIME), $cur[0], $cur[1]);
      return $idt;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::FILEUPLOAD)
    {
      $file = new XmlInputFile($field->fieldCaption, $field->fieldName);
      return $file;
    }

    else
    {
//      XmlInputLabelField xlf
      $xlf = new XmlInputLabelField($field->fieldCaption, $curValue);
      return $xlf;
    }
  }

}

?>