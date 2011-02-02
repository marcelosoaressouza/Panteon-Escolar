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

class TipoMidiaDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "tipo_midia";
  protected $_nome_modulo = "tipomidia";
  protected $_titulo_entidade = "Tipo de Midia";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($permissao = "")
  {
    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    $fileUpload = new ProcessPageStateBaseSaveFormatterFileUpload($this->_context, "upload/tipomidia");

    // Inicio dos Campos do ProcessPageFields

    // Inicio Campos da Entidade

    $field = ProcessPageFields::FactoryMinimal("nome_tipo_midia", "Tipo Mídia", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("caminho_foto_tipo_midia", "Arquivo", 32, true, true);
    $field->fieldXmlInput = XmlInputObjectType::FILEUPLOAD;
    $field->saveDatabaseFormatter = $fileUpload;
    $field->editListFormatter = new PanteonEscolarUploadTipoMidiaFormatter();
    $fieldList->addProcessPageField($field);

    if($this->_context->ContextValue("acao") == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_".$this->_nome_entidade, "Apagar?", 1, true, true);
      $field->editable = false;
      $field->editListFormatter = new PanteonEscolarApagarFormatter($this->_context, "tipo_midia", "configtipomidia");
      $fieldList->addProcessPageField($field);

    }

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

    if($this->_context->ContextValue("caminho_foto_tipo_midia") != "")
    {
      $fileProcessor = new UploadFilenameProcessor('*.*', $this->_context);
      $fileProcessor->setFilenameLocation(ForceFilenameLocation::DefinePath, "upload/tipomidia");
      $resultUpload = $this->_context->processUpload($fileProcessor, false, 'caminho_foto_tipo_midia');

    }

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

  public function TipoMidiaDBXML($context, $nome_modulo = "tipomidia", $titulo = "Tipo Midia")
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
