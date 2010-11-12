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

class PerfilDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;

  protected $_nome_entidade = "perfil";
  protected $_nome_modulo = "perfil";
  protected $_titulo_entidade = "Perfil";
  protected $_num_registros_padrao = 5;

  public function criarProcessPageFields($id_usuario = "", $permissao = "") {
    // Inicio Obtencao de dados de Tabelas Auxiliares-Relacionadas
    $sql = "SELECT * FROM estado";
    $db = new PerfilDB($this->_context);
    $it = $db->obterEstados();
    $arrayEstado = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "uf", "nome");

    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas

    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // File Upload
    $fileUpload = new ProcessPageStateBaseSaveFormatterFileUpload($this->_context, "upload/fotos");
    $fileUpload->resizeImageTo(100, 100);

    // Inicio Campos da Entidade
    $field = ProcessPageFields::FactoryMinimal("data_nascimento_perfil", "Data Nascimento", 8, true, true);
    $field->fieldXmlInput = XmlInputObjectType::DATE;
    $field->editListFormatter = new PanteonEscolarDataFormatter();
    $field->editFormatter = new PanteonEscolarDataFormatter();
    $field->saveDatabaseFormatter = new PanteonEscolarDataMySQLFormatter();
    $field->defaultValue = DateUtil::Today(DATEFORMAT::DMY, "/", true);
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("uf_estado", "Estado", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayEstado;
    $fieldList->addProcessPageField($field);

    $db = new PerfilDB($this->_context);
    $id_cidade = $db->obterPorIdUsuario($id_usuario)->getIDCidade();
    $uf_estado = $db->obterPorIdUsuario($id_usuario)->getUFEstado();

    if($id_cidade == "") $arrayCidade = array(0 => "Escolha um Estado");
    else $arrayCidade = PanteonEscolarBaseDBAccess::getArrayFromIterator($db->obterCidadesPorUFEstado($uf_estado), "id_cidade", "nome");

    $field = ProcessPageFields::FactoryMinimal("id_cidade", "Cidade", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
    $field->arraySelectList = $arrayCidade;

    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("texto_perfil", "Meu Perfil", NULL, true, true);
    $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;
    $field->editListFormatter = new PanteonEscolarPerfilFormatter();
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("caminho_foto_perfil", "Foto", 36, false, false);
    $field->fieldXmlInput = XmlInputObjectType::FILEUPLOAD;
    $field->saveDatabaseFormatter = $fileUpload;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_usuario", "Usuário", 4, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_usuario;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_hora_atualizacao_perfil", "Data/Hora Atualização", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
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

    if($this->_context->ContextValue("caminho_foto_perfil") != "") {
      $fileProcessor = new UploadFilenameProcessor('*.*', $this->_context);
      $fileProcessor->setFilenameLocation(ForceFilenameLocation::DefinePath, "upload/fotos");
      $resultUpload = $this->_context->processUpload($fileProcessor, false, 'caminho_foto_perfil');

    }

    if($permissao) $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
    else $processpage->setPermissions(false, false, false, false);

    // Filtros
    $filtro = "";

    if($id_usuario != "") $filtro .= " id_usuario = "  . $id_usuario ." ";

    if($filtro != "") $processpage->setFilter($filtro);

    return $processpage;

  }

  public function generateObject($current) {
    $span1 = new XmlnukeSpanCollection();
    $span1->addXmlnukeObject($this->criarProcessPageFields());
    $node = XmlUtil::CreateChild($current, $this->_nome_entidade, "");
    $body = XmlUtil::CreateChild($node, "body", "");
    parent::generatePage($body);

  }

  public function PerfilDBXML($context, $num_reg = "", $nome_modulo = "") {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_num_registros_padrao = $num_reg;
    $this->_nome_modulo = $nome_modulo;

  }

}

?>
