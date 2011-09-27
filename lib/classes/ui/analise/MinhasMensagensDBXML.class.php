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

class MinhasMensagensDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  /**
   * @var Context
   */
  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 5;
  protected $_action;
  protected $_nome_entidade = "mensagem_usuario";
  protected $_nome_modulo = "mensagemusuario";
  protected $_titulo_entidade = "Mensagem do Usuário";

  public function generateObject($current)
  {
    $id_tema_panteon = $this->_context->getCookie("id_tema_panteon_definido");
    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    if($id_tema_panteon == "")
    {
      $this->_context->redirectUrl("module:panteonescolar.meustemaspanteon");
    }

    $container = PanteonEscolarBaseModule::caixaAviso($this->_context);

    // Mensagem de Avisos
    $span1->addXmlnukeObject(PanteonEscolarBaseModule::aviso($this->_context));

    if($this->_opcao == "listarDireita")
    {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node, 'Dica Mensagens');
      $body = PanteonEscolarBaseModule::preencherBarraComTexto($node, '', 'Aqui, você pode receber e enviar mensagens para seus colegas e professor(a).', '');

      if(($nivel_acesso == "GESTOR") || ($nivel_acesso == "ADMINISTRADOR") || ($nivel_acesso == "EDITOR"))
      {
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");
      }
    }

    if($this->_opcao == "processPageField")
    {

      //Debug::PrintValue("ACAO: " . $this->_context->ContextValue("acao"));

      $id_usuario_dest = "";
      $permissao = array(true, true, true, true);


      //Trecho de codigo para enviar e-mail apos salvar
      if($this->_context->ContextValue("acao") == "ppmsgs" && ($this->_context->ContextValue("ppmsgtext") == "ppnew_confirm" || $this->_context->ContextValue("ppmsgtext") == "ppedit_confirm"))
      {

        $arrDestinatario = array();

        $dbUsuario = new UsuarioDB($this->_context);

        $nomeRemetente = $dbUsuario->obterPorId($id_usuario)->getNomeCompletoUsuario()." (".$dbUsuario->obterPorId($id_usuario)->getLoginUsuario().")";

        switch($this->_context->ContextValue("ppmsgtext"))
        {
        case "ppnew_confirm":
          //obter a maxima mensagem inserida pelo usuario $id_usuario
          $dbMensagemUsuario = new MensagemUsuarioDB($this->_context);
          $assunto = $dbMensagemUsuario->obterUtilmaMensagemUsuario($id_usuario)->getNomeMensagemUsuario();
          $texto = $dbMensagemUsuario->obterUtilmaMensagemUsuario($id_usuario)->getTextoMensagemUsuario();
          //Converte a string de ids de destino para array
          $arrDestinatario = explode(",", $dbMensagemUsuario->obterUtilmaMensagemUsuario($id_usuario)->getIDUsuarioDest());

          break;

        case "ppedit_confirm":
          //obter a mensagem editada pelo usuario

          $idMensagem = $this->_context->getSession("valueid_ppedit");

          //Debug::PrintValue("ID mensagem: " . $idMensagem);
          $dbMensagemUsuario = new MensagemUsuarioDB($this->_context);
          $assunto = $dbMensagemUsuario->obterPorId($idMensagem)->getNomeMensagemUsuario();
          $texto = $dbMensagemUsuario->obterPorId($idMensagem)->getTextoMensagemUsuario();

          //Converte a string de ids de destino para array
          $arrDestinatario = explode(",", $dbMensagemUsuario->obterPorId($idMensagem)->getIDUsuarioDest());

          break;
        }

        //Testa os valores das variaveis e chama funcao de enviar e-mail
        if(!empty($assunto) && !empty($texto) && count($arrDestinatario) > 0)
        {
          //Formata a mensagem com De, Assunto e Mensagem
          $mensagem = "<b>De:</b> ".$nomeRemetente."<br/><b>Assunto: </b>".$assunto."<br/><b>Mensagem:</b><br/>".$texto;
          $this->EnviarMensagemPorEmail($assunto, $mensagem, $arrDestinatario);
        }
      }

      //Coloca o valueid na sessao devido ao problema de limpar as variveis apos o post
      if($this->_context->ContextValue("acao") == "ppedit")
      {
        $this->_context->setSession("valueid_ppedit", $this->_context->ContextValue("valueid"));
      }


      //Para exibir os campos na opcao de visualizar
      if($this->_context->ContextValue("acao") == "ppview")
      {
        $pagina = $this->VisualizarMensagemUsuario();
        $span1->addXmlnukeObject($pagina);
      }


      //Para exibir os campos na opcao de nova mensagem
      if($this->_context->ContextValue("acao") == "ppnew" || $this->_context->ContextValue("acao") == "ppedit")
      {

        $id_usuario_dest = "";

        if($this->_context->ContextValue("acao") == "ppnew")
        {
          $titulo = "Nova mensagem";
        }

        else
        {
          $titulo = "Editar mensagem";
        }

        $aviso = new XmlInputLabelObjects("<p></p>");
        $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">'.$titulo.'</div>'));
        $span1->addXmlnukeObject($aviso);

        $dbxml = new MensagemUsuarioDBXML($this->_context, "minhasmensagens", $titulo);
        $pagina = $dbxml->criarProcessPageFields($id_usuario, $id_tema_panteon, "", $id_usuario_dest, $permissao);
        $span1->addXmlnukeObject($this->filtro());
        $span1->addXmlnukeObject($pagina);
      }


      //Para excluir mensagem recebida tratando o campo id_mensagem_dest
      if($this->_context->ContextValue("acao") == "excluirMensagem")
      {

        $idMensagem = $this->_context->ContextValue("valueid");

        $dbMensagemUsuario = new MensagemUsuarioDB($this->_context);
        //Converte a string de ids de destino para array
        $arrDestinatario = explode(",", $dbMensagemUsuario->obterPorId($idMensagem)->getIDUsuarioDest());

        //Verifica se existe mais de um destinatario para a mensagem
        if(count($arrDestinatario)>1)
        {

          if(in_array($id_usuario, $arrDestinatario))
          {
            //remove o $id_usuario do usuario logado
            $chave = array_search($id_usuario, $arrDestinatario); //localiza o valor no array
            unset($arrDestinatario[$chave]); //remove o destinatario do array

            $str_id_usuario_dest = implode(",",$arrDestinatario);

            //Atualiza o registro
            $dbMensagemUsuario->atualizarDestinatarioMensagemUsuario($idMensagem, $str_id_usuario_dest);

            $this->_context->addCookie("mensagem_aviso","A mensagem recebida foi apagada com sucesso!");
          }
        }

        else
        {
          if(!empty($arrDestinatario[0]))
          {
            //realiza a exclusao da mensagem
            $dbMensagemUsuario->excluirMensagemUsuarioRecebida($idMensagem);
            $this->_context->addCookie("mensagem_aviso","A mensagem recebida foi apagada com sucesso!");
          }
        }

        //Redireciona para a lista de minhas mensagens
        $this->_context->redirectUrl("module:panteonescolar.minhasmensagens");
      }

      if($this->_opcao == "ver")
      {
        $db = new PerfilDB($this->_context);
        $modelPerfil = $db->obterPorId($id_perfil_ver);

        $db = new PerfilDB($this->_context);
        $cidade = $db->obterCidadePorIDCidade($modelPerfil->getIDCidade());
        $texto_perfil = $modelPerfil->getTextoPerfil();
        $dt_nasc = DateUtil::ConvertDate($modelPerfil->getDataNascimentoPerfil(), DATEFORMAT::YMD, DATEFORMAT::DMY, "/", false);
        $idade = PanteonEscolarBaseModule::idade($modelPerfil->getDataNascimentoPerfil());

        $info_nasc = '<div id="subtitulos">Data Nascimento: </div><div id="textover">'.$dt_nasc.' ('.$idade.' anos)'.'</div>';

        if($modelPerfil->getCaminhoFotoPerfil() != "")
        {
          $span1->addXmlnukeObject(new XmlNukeText('<div id="caixa_info_perfil_foto">'));
          $span1->addXmlnukeObject(new XmlNukeText('<img alt="Foto" src="'.$modelPerfil->getCaminhoFotoPerfil().'"/>'));
          $span1->addXmlnukeObject(new XmlNukeText('</div>'));
        }


        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Cidade: </div><div id="textover">'.$cidade.'</div>'));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Estado: </div><div id="textover">'.$modelPerfil->getUFEstado().'</div>'));
        $span1->addXmlnukeObject(new XmlNukeText($info_nasc));
        $span1->addXmlnukeObject(new XmlNukeText('<br/><div id="subtitulos">Perfil Resumido: </div><div id="textover">'.$texto_perfil.'</div>'));

      }

      if($this->_context->ContextValue("acao") == "" || $this->_context->ContextValue("acao") == "ppmsgs" || $this->_context->ContextValue("acao") == "move" || ($this->_context->ContextValue("acao") == "ppedit_confirm" && $this->_context->ContextValue("chamada") == "1") || ($this->_context->ContextValue("acao") == "ppdelete" && $this->_context->ContextValue("chamada") == "1") || ($this->_context->ContextValue("acao") == "ppnew_confirm" && $this->_context->ContextValue("chamada") == "1"))
      {

        $id_usuario_dest = $id_usuario;

        //Para nivel de administrador, gestor e mediador - pode editar mensagem
//                if (($nivel_acesso == "GESTOR") || ($nivel_acesso == "ADMINISTRADOR") || ($nivel_acesso == "MEDIADOR")){
//                    $permissao = array(false, false, true, true);
//                }else{
        $permissao = array(false, true, false, true);
//                }
        //Para exibir as mensagens recebidas
        $dbxml = new MensagemUsuarioDBXML($this->_context, "minhasmensagens", "Mensagens Recebidas");
        $pagina = $dbxml->criarProcessPageFields($id_usuario, $id_tema_panteon, "", $id_usuario_dest, $permissao);

        if(($pagina->getAllRecords()->Count() > 0))
        {
          $span1->addXmlnukeObject($this->filtro());
          $span1->addXmlnukeObject($pagina);
        }

        else
        {
          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma mensagem recebida.<br/></div>'));
          $span1->addXmlnukeObject($aviso);
        }

        $id_usuario_dest = "";
        $permissao = array(true, true, true, true);
        $dbxml = new MensagemUsuarioDBXML($this->_context, "minhasmensagens", "Mensagens Enviadas");
        $pagina = $dbxml->criarProcessPageFields($id_usuario, $id_tema_panteon, "", $id_usuario_dest, $permissao);

        if(($pagina->getAllRecords()->Count() > 0))
        {
          $span1->addXmlnukeObject($this->filtro());
          $span1->addXmlnukeObject($pagina);
        }

        else
        {
          $aviso = new XmlInputLabelObjects("<p></p>");
          $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Nenhuma mensagem enviada.<br/> <a href="' . PanteonEscolarBaseModule::curPageURL() . '&acao=ppnew&chamada=1">Enviar mensagem</a></div>'));
          $span1->addXmlnukeObject($aviso);
        }
      }


      //Para resposta de mensagem
      if($this->_context->ContextValue("acao") == "responderMensagem")
      {

        $idMensagemOriginal = $this->_context->ContextValue("valueid");
        $dbMensagemUsuario = new MensagemUsuarioDB($this->_context);

        $dbUsuario = new UsuarioDB($this->_context);

        $nomeMensagemOriginal = $dbMensagemUsuario->obterPorId($idMensagemOriginal)->getNomeMensagemUsuario();
        $idUsuarioMensagemOriginal = $dbMensagemUsuario->obterPorId($idMensagemOriginal)->getIDUsuarioOrig();
        $nomeUsuarioMensagemOriginal = $dbUsuario->obterPorId($idUsuarioMensagemOriginal)->getNomeCompletoUsuario();

        $arrUsuarioOrig = array();
        $arrUsuarioOrig[$idUsuarioMensagemOriginal] = $nomeUsuarioMensagemOriginal;

        $aviso = new XmlInputLabelObjects("<p></p>");
        $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Responder Mensagem<br/></div>'));
        $span1->addXmlnukeObject($aviso);

        $dbxml = new MensagemUsuarioDBXML($this->_context, "minhasmensagens", "Responder Mensagem");
        $pagina = $dbxml->criarProcessPageFields($id_usuario, $id_tema_panteon, "", $id_usuario_dest, $permissao, $arrUsuarioOrig, $nomeMensagemOriginal);
        $pagina->forceCurrentAction("ppnew");

        $span1->addXmlnukeObject($this->filtro());
        $span1->addXmlnukeObject($pagina);
      }
    }


    // Inicio - menu
    //
    if($this->_opcao == "menu")
    {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenu($node, PanteonEscolarBaseModule::preencherMenuUsuario(PanteonEscolarMenu::Forum));
    }

    //
    // Fim - menu
    // Inicio - menu head
    //
    if($this->_opcao == "menuHead")
    {
      $nodeHead = XmlUtil::CreateChild($current, "blockhead", "");
      XmlUtil::AddAttribute($nodeHead, "perfil", strtolower($nivel_acesso));

      $msg = "Bem-Vindo, " . ucfirst($this->_context->authenticatedUser()) . " (" . $nivel_acesso . ").";
      $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'meutemapanteon'));
      XmlUtil::AddAttribute($node, "nome_usuario", $msg);
      XmlUtil::AddAttribute($node, "logout", "true");
    }

    //
    // Fim - menu head


    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);
  }

  public function filtro()
  {
    $span = new XmlnukeSpanCollection();
    $formPost = "module:panteonescolar.minhasmensagens";
    $form = new XmlFormCollection($this->_context, $formPost, "Minhas Mensagens");
    //$form->addXmlnukeObject($this->filtroMinhasMensagens());
    //$buttons = new XmlInputButtons();
    //$buttons->addSubmit("Pesquisar");
    //$form->addXmlnukeObject($buttons);

    $span->addXmlnukeObject($form);

    return $span;
  }

  public function filtroMinhasMensagens()
  {
    $listaMinhasMensagens = array(1 => "Mensagens Enviadas por Mim", 2 => "Mensagens Recebidas"); //0 => "-- Selecione --", , 3 => "Todas as Mensagens"

    $id_minhas_mensagens_filtro_selecionada = $this->_context->ContextValue("id_minhas_mensagens_filtro");
    $lista = new XmlEasyList(EasyListType::SELECTLIST, "id_minhas_mensagens_filtro", "Minhas Mensagens", $listaMinhasMensagens, $id_minhas_mensagens_filtro_selecionada);

    return $lista;
  }

  public function MinhasMensagensDBXML($context, $opcao, $action)
  {
    if(!($context instanceof Context))
    {
      throw new Exception("Falta de Context");
    }

    $this->_context = $context;
    $this->_opcao = $opcao;
    $this->_action = $action;
  }

  /**
   * @method Metodo para enviar e-mail com mensagem para os usuarios
   * @param string $assunto
   * @param string $texto
   * @param array $arrDestinatario
   */
  public function EnviarMensagemPorEmail($assunto, $texto, $arrDestinatario)
  {

    $assunto = PanteonEscolarConsts::ComplementoAssuntoMensagemUsuario . $assunto;
    $texto = $texto . PanteonEscolarConsts::TextoRodapeEmailMensagemUsuario;

    if(count($arrDestinatario) > 0)
    {

      //Formata o e-mail do remetente para Panteon Escolar <panteon@alfabetizacao.kinghost.net>
      $remetente_email = MailUtil::getFullEmailName(PanteonEscolarConsts::NomeRemetente, PanteonEscolarConsts::EmailRemetente);

      foreach($arrDestinatario as $id_destinatario)
      {

        $dbUsuario = new UsuarioDB($this->_context);

        //Obtem nome do usuario
        $nomeDestinatario = $dbUsuario->obterPorId($id_destinatario)->getNomeCompletoUsuario();
        //Obtem o e-mail do usuario
        $destinatario = $dbUsuario->obterPorId($id_destinatario)->getEmailUsuario();

        if(!empty($destinatario))
        {
          $strDestinatario = $nomeDestinatario . " <" . $destinatario . ">";

          MailUtil::Mail($this->_context, $remetente_email, $destinatario, $assunto, "", "", $texto, true);
        }
      }

    }
  }

  /**
   * @author Roberto Rander rrander at gmail.com
   * @return XmlContainerCollection
   */
  public function VisualizarMensagemUsuario()
  {

    $span1 = new XmlnukeSpanCollection();
    $this->addXmlnukeObject($span1);

    $idMensagem = $this->_context->ContextValue("valueid");

    $dbMensagemUsuario = new MensagemUsuarioDB($this->_context);
    $dbUsuario = new UsuarioDB($this->_context);
    $dbMensagemUsuario->atualizaMensagemLida($idMensagem);

    $assunto = $dbMensagemUsuario->obterPorId($idMensagem)->getNomeMensagemUsuario();
    $texto = $dbMensagemUsuario->obterPorId($idMensagem)->getTextoMensagemUsuario();

    $idUsuarioRemetente = $dbMensagemUsuario->obterPorId($idMensagem)->getIDUsuarioOrig();
    $nomeRemetenteMensagem = $dbUsuario->obterPorId($idUsuarioRemetente)->getNomeCompletoUsuario()." (".$dbUsuario->obterPorId($idUsuarioRemetente)->getLoginUsuario().")";
    $arrayUsuarioDest = explode(",", $dbMensagemUsuario->obterPorId($idMensagem)->getIDUsuarioDest());

    $nomeDestinatarios = "";
    foreach($arrayUsuarioDest as $id_destinatario)
    {
      if(!empty($id_destinatario))
      {
        //Nome (login)
        $nomeDestinatarios .= $dbUsuario->obterPorId($id_destinatario)->getNomeCompletoUsuario()." (".$dbUsuario->obterPorId($id_destinatario)->getLoginUsuario().")<br/>";
      }
    }

    $data_hora = $dbMensagemUsuario->obterPorId($idMensagem)->getDataHoraCadastroMensagemUsuario();
    $data = DateUtil::ConvertDate($data_hora, DATEFORMAT::YMD, DATEFORMAT::DMY, "/", true);

    $div = new XmlContainerCollection();

    $aviso = new XmlInputLabelObjects("<p></p>");
    $aviso->addXmlnukeObject(new XmlNukeText('<div id="meusPontosDeVistas">Detalhes da Mensagem</div>'));
    $div->addXmlnukeObject($aviso);

    $formPost = "module:panteonescolar.minhasmensagens";
    $form = new XmlFormCollection($this->_context, $formPost, "Detalhes da Mensagem");

    $aviso = new XmlInputLabelObjects('Remetente:');
    $aviso->addXmlnukeObject(new XmlNukeText($nomeRemetenteMensagem));
    $form->addXmlnukeObject($aviso);

    $aviso = new XmlInputLabelObjects('Destinatário(s):');
    $aviso->addXmlnukeObject(new XmlNukeText($nomeDestinatarios));
    $form->addXmlnukeObject($aviso);

    $aviso = new XmlInputLabelObjects('Data e horário:');
    $aviso->addXmlnukeObject(new XmlNukeText($data));
    $form->addXmlnukeObject($aviso);

    $aviso = new XmlInputLabelObjects('Assunto:');
    $aviso->addXmlnukeObject(new XmlNukeText($assunto));
    $form->addXmlnukeObject($aviso);

    $aviso = new XmlInputLabelObjects('Mensagem:');
    $aviso->addXmlnukeObject(new XmlNukeText($texto));
    $form->addXmlnukeObject($aviso);

    $div->addXmlnukeObject($form);

    return $div;
  }

}

?>