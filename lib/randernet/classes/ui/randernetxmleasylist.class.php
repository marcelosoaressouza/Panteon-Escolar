<?php

class RanderNetEasyListType extends EasyListType
{
  const RANDERNET_SELECTLIST_AJAX = 6;
  const RANDERNET_SELECTLIST_AJAX_REQUEST = 7;
}

/**
 * Classe do RanderNetXmlDualList
 *
 * @author Ricardo Mangabeira <rasmangabeira@randernet.com.br>
 * @author Roberto Rander <rrander@randernet.com.br>
 * @package RanderNet
 * @subpackage com.xmlnuke
 */
class RanderNetXmlEasyList extends XmlEasyList
{

  private $_randernet_ajax_module_name;
  private $_randernet_ajax_select_name;
  private $_randernet_ajax_parametros;

  public function setRanderNetDadosAjax($module_name, $select_name, $parametros="")
  {
    if(empty($module_name))
    {
      throw new XMLNukeException(0, "Favor informar o nome do modulo!");
    }

    if(empty($select_name))
    {
      throw new XMLNukeException(0, "Favor informar o nome do combo que sera recarregado via Ajax!");
    }

    $this->_randernet_ajax_module_name = $module_name;
    $this->_randernet_ajax_select_name = $select_name;
    $this->_randernet_ajax_parametros = $parametros;
  }

  /**
   * XmlEditList constructor
   *
   * @param EasyListType $listType
   * @param string $name
   * @param string $caption
   * @param array $values
   * @param string $selected
   * @return XmlEasyList
   */
  public function __construct($listType, $name, $caption, $values, $selected = null)
  {
    parent::__construct($listType, $name, $caption, $values, $selected);
  }

  /**
   *  Contains specific instructions to generate all XML informations
   *  This method is processed only one time
   *  Usually is the last method processed
   *
   * @param DOMNode $current
   */
  public function generateObject($current)
  {
    $nodeWorking = null;

    // Criando o objeto que conterá a lista
    switch($this->_easyListType)
    {
    case EasyListType::CHECKBOX:
    {
      XmlUtil::CreateChild($current, "caption", $this->_caption);
      $nodeWorking = $current;
      $iHid = new XmlInputHidden("qty" . $this->_name, sizeof($this->_values));
      $iHid->generateObject($nodeWorking);
      break;
    }

    case EasyListType::RADIOBOX:
    {
      XmlUtil::CreateChild($current, "caption", $this->_caption);
      $nodeWorking = $current;
      break;
    }

    case EasyListType::SELECTLIST:
    case EasyListType::SELECTIMAGELIST:
    {
      if($this->_readOnly)
      {
        if($this->_easyListType == EasyListType::SELECTLIST)
        {
          $deliLeft = (strlen($this->_readOnlyDeli) != 0 ? $this->_readOnlyDeli[0] : "");
          $deliRight = (strlen($this->_readOnlyDeli) != 0 ? $this->_readOnlyDeli[1] : "");

          //XmlInputHidden $xih
          //XmlInputLabelField $xlf
          $xlf = new XmlInputLabelField($this->_caption, $deliLeft . $this->_values[$this->_selected] . $deliRight);
          $xih = new XmlInputHidden($this->_name, $this->_selected);
          $xlf->generateObject($current);
          $xih->generateObject($current);
        }

        elseif($this->_easyListType == EasyListType::SELECTIMAGELIST)
        {
          $img = new XmlnukeImage($this->_values[$this->_selected]);
          $img->generateObject($current);
        }
        return;
      }

      else
      {
        $nodeWorking = XmlUtil::CreateChild($current, "select", "");
        //$nodeWorking = XmlUtil::CreateChild($current, "randernet_ajax_select", "");

        if(!empty($this->_randernet_ajax_module_name))
        {

          // XmlUtil::AddAttribute($nodeWorking, "randernet_ajax", "true");


          XmlUtil::AddAttribute($nodeWorking, "randernet_ajax_module_name", $this->_randernet_ajax_module_name);
          XmlUtil::AddAttribute($nodeWorking, "randernet_ajax_select_name", $this->_randernet_ajax_select_name);

          if(!empty($this->_randernet_ajax_parametros))
          {
            XmlUtil::AddAttribute($nodeWorking, "randernet_ajax_parametros", $this->_randernet_ajax_parametros);
          }

        }

        XmlUtil::AddAttribute($nodeWorking, "caption", $this->_caption);
        XmlUtil::AddAttribute($nodeWorking, "name", $this->_name);

        if($this->_required)
        {
          XmlUtil::AddAttribute($nodeWorking, "required", "true");
        }

        if($this->_size > 1)
        {
          XmlUtil::AddAttribute($nodeWorking, "size", $this->_size);
        }

        if($this->_easyListType == EasyListType::SELECTIMAGELIST)
        {
          XmlUtil::AddAttribute($nodeWorking, "imagelist", "true");
          XmlUtil::AddAttribute($nodeWorking, "thumbnailsize", $this->_thumbnailSize);
          XmlUtil::AddAttribute($nodeWorking, "notfoundimage", $this->_notFoundImage);
          XmlUtil::AddAttribute($nodeWorking, "noimage", $this->_noImage);
        }
      }

      break;
    }

    case RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX:
    case RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX_REQUEST:
    {
      if($this->_readOnly)
      {
        if($this->_easyListType == EasyListType::SELECTLIST)
        {
          $deliLeft = (strlen($this->_readOnlyDeli) != 0 ? $this->_readOnlyDeli[0] : "");
          $deliRight = (strlen($this->_readOnlyDeli) != 0 ? $this->_readOnlyDeli[1] : "");

          //XmlInputHidden $xih
          //XmlInputLabelField $xlf
          $xlf = new XmlInputLabelField($this->_caption, $deliLeft . $this->_values[$this->_selected] . $deliRight);
          $xih = new XmlInputHidden($this->_name, $this->_selected);
          $xlf->generateObject($current);
          $xih->generateObject($current);
        }

        elseif($this->_easyListType == EasyListType::SELECTIMAGELIST)
        {
          $img = new XmlnukeImage($this->_values[$this->_selected]);
          $img->generateObject($current);
        }
        return;
      }

      else
      {
        if($this->_easyListType == RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX_REQUEST)
        {
          $nodeWorking = XmlUtil::CreateChild($current, "randernet_ajax_select", "");
        }

        else
        {
          $nodeWorking = XmlUtil::CreateChild($current, "select", "");
        }

        //$nodeWorking = XmlUtil::CreateChild($current, "randernet_ajax_select", "");

        XmlUtil::AddAttribute($nodeWorking, "randernet_ajax", "true");
        XmlUtil::AddAttribute($nodeWorking, "caption", $this->_caption);
        XmlUtil::AddAttribute($nodeWorking, "name", $this->_name);

        if($this->_required)
        {
          XmlUtil::AddAttribute($nodeWorking, "required", "true");
        }

        if($this->_size > 1)
        {
          XmlUtil::AddAttribute($nodeWorking, "size", $this->_size);
        }


        if($this->_easyListType == EasyListType::SELECTIMAGELIST)
        {
          XmlUtil::AddAttribute($nodeWorking, "imagelist", "true");
          XmlUtil::AddAttribute($nodeWorking, "thumbnailsize", $this->_thumbnailSize);
          XmlUtil::AddAttribute($nodeWorking, "notfoundimage", $this->_notFoundImage);
          XmlUtil::AddAttribute($nodeWorking, "noimage", $this->_noImage);
        }
      }

      break;
    }

    case EasyListType::UNORDEREDLIST:
    {
      XmlUtil::CreateChild($current, "b", $this->_caption);
      $nodeWorking = XmlUtil::CreateChild($current, "ul", "");
      break;
    }
    }

    $i = 0;

    foreach($this->_values as $key => $value)
    {
      switch($this->_easyListType)
      {
      case EasyListType::CHECKBOX:
      {
//          XmlInputCheck $iCk
        $iCk = new XmlInputCheck($value, $this->_name .($i++), $key);
        $iCk->setType(InputCheckType::CHECKBOX);
        $iCk->setChecked($key == $this->_selected);
        $iCk->setReadOnly($this->_readOnly);
        $iCk->generateObject($nodeWorking);
        break;
      }

      case EasyListType::RADIOBOX:
      {
//          XmlInputCheck $iCk
        $iCk = new XmlInputCheck($value, $this->_name, $key);
        $iCk->setType(InputCheckType::RADIOBOX);
        $iCk->setChecked($key == $this->_selected);
        $iCk->setReadOnly($this->_readOnly);
        $iCk->generateObject($nodeWorking);
        break;
      }

      case EasyListType::SELECTLIST:
      case EasyListType::SELECTIMAGELIST:
      case RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX:
      case RanderNetEasyListType::RANDERNET_SELECTLIST_AJAX_REQUEST:
      {
        $node = XmlUtil::CreateChild($nodeWorking, "option", "");
        XmlUtil::AddAttribute($node, "value", $key);

        if($key == $this->_selected)
        {
          XmlUtil::AddAttribute($node, "selected", "yes");
        }

        XmlUtil::AddTextNode($node, $value);
        break;
      }

      case EasyListType::UNORDEREDLIST:
      {
        XmlUtil::CreateChild($nodeWorking, "li", $value);
        break;
      }
      }
    }
  }

}

?>