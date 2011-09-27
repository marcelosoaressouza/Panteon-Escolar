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

class RanderNetXmlnukeGenericCollection extends XmlnukeCollection implements IXmlnukeDocumentObject
{
  protected $_tagName = "";

  public function RanderNetXmlnukeGenericCollection($tagName)
  {
    $this->_tagName = $tagName;
  }

  /**
  *@desc Contains specific instructions to generate all XML informations. This method is processed only one time. Usually is the last method processed.
  *@param DOMNode $current
  *@return void
  */
  public function generateObject($current)
  {
    if(empty($this->_tagName))
    {
      throw new XMLNukeException(0, "Favor informar o nome da tag");
    }

    $node = XmlUtil::CreateChild($current, $this->_tagName, "");
    $this->generatePage($node);
  }

}
?>