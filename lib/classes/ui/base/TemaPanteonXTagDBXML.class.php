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

class TemaPanteonXTagDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "tema_panteon_x_tag";
  protected $_nome_modulo = "temapanteonxtag";
  protected $_titulo_entidade = "Tema Panteon X Tag";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($permissao = "")
  {

    // Inicio Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new TemaPanteonDB($this->_context);
    $it = $db->obterTodos();
    $arrayTemaPanteon = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                        "id_tema_panteon",
                        "nome_tema_panteon");

    $db = new TagDB($this->_context);
    $it = $db->obterTodos();
    $arrayTag = PanteonEscolarBaseDBAccess::getArrayFromIterator($it,
                "id_tag",
                "nome_tag");
    //
    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayTemaPanteon;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_tag", "Tag", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayTag;
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields

    $processpage = new PanteonEscolarMyProcess($this->_context,
        $fieldList,
        $this->_titulo_entidade,
        "module:panteonescolar.".$this->_nome_modulo."&amp;chamada=1",
        NULL,
        $this->_nome_entidade,
        PanteonEscolarBaseDBAccess::DATABASE());

    if($permissao)
    {
      $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    }

    else
    {
      $processpage->setPermissions(false, false, false, false);
    }

    return $processpage;

  }

  public function generateObject($current)
  {
    $span1 = new XmlnukeSpanCollection();
    $span1->addXmlnukeObject($this->criarProcessPageFields());
    $node = XmlUtil::CreateChild($current, $this->_nome_entidade, "");
    $body = XmlUtil::CreateChild($node, "body", "");
    parent::generatePage($body);

  }

  public function TemaPanteonXTagDBXML($context, $nome_modulo = "temapanteonxtag", $titulo = "Tema Panteon X Tag")
  {
    if(!($context instanceof Context))
    {
      throw new Exception("Falta de Context");
    }

    $this->_context = $context;
    $this->_nome_modulo = $nome_modulo;
    $this->_titulo_entidade = $titulo;

  }

}

?>
