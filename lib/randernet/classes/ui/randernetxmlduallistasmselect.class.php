<?php

/**
 * Classe do RanderNetXmlDualListASMSelect
 *
 * @author Ricardo Mangabeira <rasmangabeira@randernet.com.br>
 * @author Roberto Rander <rrander@randernet.com.br>
 * @package RanderNet
 * @subpackage com.xmlnuke
 */
class RanderNetXmlDualListASMSelect extends XmlDualList
{

  /**
   *
   * @var string
   */
  protected $_title;

  /**
   * Texto do titulo do DualList
   * @param string $title
   */
  public function setTitle($title)
  {
    $this->_title = $title;
  }

  /**
   * SigapXmlDualList constructor
   *
   * @param Context $context
   * @param string $name
   * @param string $caption
   */
  public function __construct($context, $name, $caption="")
  {
    parent::__construct($context, $name, $caption, "");
  }

  /**
   * @desc Generate $page, processing yours childs.
   * @param DOMNode $current
   * @return void
   */
  public function generateObject($current)
  {
    // no principal
    $nodeWorking = XmlUtil::CreateChild($current, "randernet_duallist_asmselect", "");

    XmlUtil::AddAttribute($nodeWorking, "name", $this->_name);
    $leftList = XmlUtil::CreateChild($nodeWorking, "leftlist", "");

    XmlUtil::AddAttribute($leftList, "name", $this->_listLeftName);
    XmlUtil::AddAttribute($leftList, "caption", $this->_listLeftCaption);
    XmlUtil::AddAttribute($leftList, "title", $this->_title);

    $arrSelecteds = array();

    if(!is_null($this->_listRightDataSource))
    {
      while($this->_listRightDataSource->hasNext())
      {
        $row = $this->_listRightDataSource->moveNext();
        $arrSelecteds[$row->getField($this->_dataTableFieldId)] = $row->getField($this->_dataTableFieldText);
      }
    }

    $arrLeft = array();

    while($this->_listLeftDataSource->hasNext())
    {
      $row = $this->_listLeftDataSource->moveNext();
      $arrLeft[$row->getField($this->_dataTableFieldId)] = $row->getField($this->_dataTableFieldText);
    }

    $this->buildListItens($leftList, $arrLeft, $arrSelecteds);
    return $nodeWorking;
  }

  /**
   * Build Dual lista data
   *
   * @param DOMNode $list
   * @param array $arr
   */
  private function buildListItens($list, $arr, $arrSelecteds)
  {
    foreach($arr as $key => $value)
    {
      $item = XmlUtil::CreateChild($list, "item", "");
      XmlUtil::AddAttribute($item, "id", $key);
      XmlUtil::AddAttribute($item, "text", $value);

      if(!empty($arrSelecteds))
      {
        if(array_key_exists($key, $arrSelecteds))
        {
          XmlUtil::AddAttribute($item, "selected", "selected");
        }
      }
    }
  }

  /**
   * Config DataSource to Dual List
   *
   * @param IIterator $listLeft
   * @param IIterator $listRight
   */
  public function setDataSource($listLeft, $selectedList = null)
  {
    $this->_listLeftDataSource = $listLeft;
    $this->_listRightDataSource = $selectedList;
  }

}

?>