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

class MensagemUsuarioDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  /**
   *
   * @var Context
   */
  protected $_context;
  protected $_nome_entidade = "mensagem_usuario";
  protected $_nome_modulo = "mensagemusuario";
  protected $_titulo_entidade = "Mensagem do Usuário";
  protected $_num_registros_padrao = 5;

  /**
   * @method Metodo para gerar o grid e campos do formulário
   * @param int $id_usuario
   * @param int $id_tema_panteon
   * @param int $id_mensagem_usuario_resposta
   * @param string $id_usuario_dest
   * @param array $permissao
   * @param array $arr_usuario_resp
   * @param string $nome_mensagem_resp
   * @return PanteonEscolarMyProcess
   */
  public function criarProcessPageFields($id_usuario = "", $id_tema_panteon = "", $id_mensagem_usuario_resposta = "", $id_usuario_dest = "", $permissao = "", $arr_usuario_resp= "", $nome_mensagem_resp= "")
  {

    // Inicio da Obtencao de dados de Tabelas Auxiliares-Relacionadas
    //
    $db = new UsuarioXTemaPanteonDB($this->_context);
    $itUsuario = $db->obterTodosOsUsuariosColetaramTemaPanteonPorIDTemaPanteon($id_tema_panteon);

    $arrayUsuarioDest = PanteonEscolarBaseDBAccess::getArrayFromIterator($itUsuario, "id_usuario", "nome_completo_usuario", "");
    unset($arrayUsuarioDest[$id_usuario]);

    //
    // Fim Obtencao de dados de Tabelas Auxiliares-Relacionadas
    // Inicio ProcessPageField
    $fieldList = new ProcessPageFields();

    // Inicio Campos da Entidade
    if($id_usuario_dest == "")
    {
      $field = ProcessPageFields::FactoryMinimal("id_usuario_dest", "Destinatário(s)", 50, true, true);
      $field->fieldXmlInput = PanteonEscolarXmlInputObjectType::RANDERNET_DUALLIST_ASMSELECT;
      $field->size = 50;

      //Debug::PrintValue("Destinatário - ".print_r($arr_usuario_resp));
      if($this->_context->ContextValue("acao") == "responderMensagem" && count($arr_usuario_resp)>0)
      {
        $field->arraySelectList = $arr_usuario_resp;
      }

      else
      {
        $field->arraySelectList = $arrayUsuarioDest;
      }

      $field->editListFormatter = new PanteonEscolarDestinatarioMensagemUsuario($this->_context);
      $fieldList->addProcessPageField($field);
    }

    $field = ProcessPageFields::FactoryMinimal("nome_mensagem_usuario", "Assunto", 255, true, true);
    $field->fieldXmlInput = XmlInputObjectType::TEXTBOX;
    $field->size = 50;

    if(!empty($nome_mensagem_resp))
    {
      $field->defaultValue = "Re: " . $nome_mensagem_resp;
    }

    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("texto_mensagem_usuario", "Mensagem", 30, true, true);
    $field->fieldXmlInput = XmlInputObjectType::HTMLTEXT;

    //Formata o texto com data se for diferente de cadastro
    if($this->_context->ContextValue("acao") != "ppnew")
    {
      $field->editListFormatter = new PanteonEscolarTextoMensagemUsuario($this->_context);
    }

    $fieldList->addProcessPageField($field);


    if($id_usuario_dest != "")
    {
      //Debug::PrintValue("Remetente ".print_r($arrayUsuarioDest));
      $field = ProcessPageFields::FactoryMinimal("id_usuario_orig", "Remetente", 30, true, true);
      $field->fieldXmlInput = XmlInputObjectType::SELECTLIST;
      $field->arraySelectList = $arrayUsuarioDest;
      $fieldList->addProcessPageField($field);
    }

    else
    {
      $field = ProcessPageFields::FactoryMinimal("id_usuario_orig", "Usuário Remetente", 30, false, false);
      $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
      $field->defaultValue = $id_usuario;
      $fieldList->addProcessPageField($field);
    }

    $field = ProcessPageFields::FactoryMinimal("id_mensagem_usuario_resposta", "Mensagem Resposta", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_mensagem_usuario_resposta;
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("data_hora_cadastro_mensagem_usuario", "Data/Hora Cadastro", 15, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = date("Y-m-d H:i:s");
    $fieldList->addProcessPageField($field);

    $field = ProcessPageFields::FactoryMinimal("id_tema_panteon", "Tema Panteon", 30, false, false);
    $field->fieldXmlInput = XmlInputObjectType::HIDDEN;
    $field->defaultValue = $id_tema_panteon;
    $fieldList->addProcessPageField($field);

    // ID da Entidade (Todos Possuem)
    $field = ProcessPageFields::FactoryMinimal("id_" . $this->_nome_entidade, "", 1, false, false);
    $field->editable = false;
    $field->key = true;
    $fieldList->addProcessPageField($field);

    // Fim dos Campos do ProcessPageFields

    if($id_usuario_dest != "")
    {
      // Inicio Custom Button for Set
      $button = new CustomButtons();

      $button->action = "responderMensagem";
      $button->alternateText = "Responder Mensagem";
      $button->message = "Responder Mensagem";
      $button->multiple = MultipleSelectType::ONLYONE;
      $button->icon = "common/editlist/ic_respondermensagem.gif";
      $button->enabled = true;

      $button2 = new CustomButtons();

      //Exibe o botao de excluir mensagem no grid de mensagens recebidas
      $button2->action = "excluirMensagem";
      $button2->alternateText = "Apagar Mensagem";
      $button2->message = "Apagar Mensagem";
      $button2->multiple = MultipleSelectType::ONLYONE;
      $button2->icon = "common/editlist/ic_excluir.gif";
      $button2->enabled = true;

    }

    $processpage = new PanteonEscolarMyProcess($this->_context,
        $fieldList,
        $this->_titulo_entidade,
        "module:panteonescolar." . $this->_nome_modulo . "&amp;chamada=1",
        array($button,$button2),
        $this->_nome_entidade);

    if($permissao)
    {
      if($id_usuario_dest != "")
      {
        //Para não exibir o botão de excluir
        $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], false);
      }

      else
      {
        $processpage->setPermissions($permissao[0], $permissao[1], $permissao[2], $permissao[3]);
      }

    }

    else
    {
      $processpage->setPermissions(false, false, false, false);
    }

    // Filtros
    $filtro = "";


    $filtro .= " id_tema_panteon = " . $id_tema_panteon . " ";

    if($id_usuario_dest == "")
    {
      $filtro = " id_tema_panteon = " . $id_tema_panteon . " ";
      $filtro .= " AND id_usuario_orig = " . $id_usuario . " ";
    }

    //Filtro para as mensagens recebidas pelo usuario
    if($id_usuario_dest != "")
    {
      $ids_mensagem_usuario = implode(",", $this->obterIdMensagemUsuarioRecebidasPorUsuarioLogado($id_usuario, $id_tema_panteon));

      if(!empty($ids_mensagem_usuario))
      {
        $filtro .= " AND id_mensagem_usuario IN (".$ids_mensagem_usuario.") ";
      }

      else
      {
        $filtro .= " AND id_mensagem_usuario = 0 ";
      }

    }

    if($filtro != "")
    {
      $processpage->setFilter($filtro);
    }

    $processpage->setSort(" data_hora_cadastro_mensagem_usuario DESC ");

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

  public function MensagemUsuarioDBXML($context, $nome_modulo = "mensagemusuario", $titulo = "Mensagem Usuário")
  {
    if(!($context instanceof Context))
    {
      throw new Exception("Falta de Context");
    }

    $this->_context = $context;
    $this->_nome_modulo = $nome_modulo;
    $this->_titulo_entidade = $titulo;
  }

  /**
   * @method Metodo para obter os ids de mensagens recebidas por determinado usuario em mensagens de detrerminado tema
   * @author Roberto Rander rrander at gmail.com
   * @param int $id_usuario
   * @param int $id_tema_panteon
   * @return array $arrIdMensagemUsuarioFiltro
   */
  public function obterIdMensagemUsuarioRecebidasPorUsuarioLogado($id_usuario, $id_tema_panteon)
  {

    $arrIdMensagemUsuarioFiltro = array();

    $mensagemUsuarioBd = new MensagemUsuarioDB($this->_context);

    $it = $mensagemUsuarioBd->obterTodasAsMensagensUsuarioPorIDTemaPanteon($id_tema_panteon);

    $arrMensagemUsuario = PanteonEscolarBaseDBAccess::getArrayFromIterator($it, "id_mensagem_usuario", "id_usuario_dest");

    foreach($arrMensagemUsuario as $id_mensagem => $str_id_usuario_dest)
    {

      $arr_id_usuario_dest = explode(",", $str_id_usuario_dest);

      //Verifica se o usuario autenticado esta na string / array do campo id_usuario_dest
      if(in_array($id_usuario, $arr_id_usuario_dest))
      {
        $arrIdMensagemUsuarioFiltro[] = $id_mensagem;
      }
    }
    //Debug::PrintValue($id_usuario, $id_tema_panteon, $arrIdMensagemUsuarioFiltro); die();
    return $arrIdMensagemUsuarioFiltro;
  }

}

?>
