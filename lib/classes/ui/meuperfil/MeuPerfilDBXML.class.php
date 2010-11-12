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

class MeuPerfilDBXML extends XmlnukeCollection implements IXmlnukeDocumentObject
{

  protected $_context;
  protected $_opcao;
  protected $_num_registros_padrao = 3;

  public function generateObject($current) {
    $id_usuario = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario);

    $span1 = new XmlnukeSpanCollection();
    $span2 = new XmlnukeSpanCollection();

    $this->addXmlnukeObject($span1);
    $this->addXmlnukeObject($span2);

    // Inicio - mostrarMeuPerfil
    //
    if(($this->_opcao == "mostrarMeuPerfil")) {

      $dbxml = new PerfilDBXML($this->_context,  1, "meuperfil");

      $permissao = array(true, true, true, true);
      $pagina = $dbxml->criarProcessPageFields($id_usuario, $permissao);

      $dbPerfil = new PerfilDB($this->_context);
      $dbUsuario = new UsuarioDB($this->_context);
      $dbInstituicao = new InstituicaoDB($this->_context);
      $dbUsuarioXNivelAcesso = new UsuarioXNivelAcessoDB($this->_context);

      $modelPerfil = new PerfilModel();
      $modelUsuario = new UsuarioModel();
      $modelInstituicao = new InstituicaoModel();

      $modelPerfil = $dbPerfil->obterPorIdUsuario($id_usuario);
      $modelUsuario = $dbUsuario->obterPorId($id_usuario);
      $modelInstituicao = $dbInstituicao->obterPorId($modelUsuario->getIDInstituicao());

      $id_perfil = $dbPerfil->obterPorIDUsuarioIDPerfil($id_usuario);
      $nivel_acesso = $dbUsuarioXNivelAcesso->obterNivelAcessoPorIDUsuario($id_usuario);

      if(($this->_context->ContextValue("acao") != "ppnew") && ($this->_context->ContextValue("acao") != "alterar")) {
        $span1->addXmlnukeObject(new XmlNukeText('<div id="caixa_info_perfil_direita">'));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Instituição: </div>'.$modelInstituicao->getNomeInstituicao()));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Nivel de  Acesso: </div>'.$nivel_acesso));
        $span1->addXmlnukeObject(new XmlNukeText('</div>'));
      }

      if($this->_context->ContextValue("acao") == "ppmsgs") $this->_context->redirectUrl("/meuperfil");

      if(($this->_context->ContextValue("acao") == "ppnew") || ($this->_context->ContextValue("chamada") == 1)) {
        $span1->addXmlnukeObject($pagina);

      } else if($this->_context->ContextValue("acao") == "alterar") {
        $pagina->forceCurrentValueId($this->_context->ContextValue("id"));
        $pagina->forceCurrentAction("ppedit");
        $span1->addXmlnukeObject($pagina);

      }

      if(($pagina->getAllRecords()->Count() > 0) && ($this->_context->ContextValue("acao") == "")) {
        $db = new PerfilDB($this->_context);
        $cidade = $db->obterCidadePorIDCidade($modelPerfil->getIDCidade());
        $texto_perfil = $modelPerfil->getTextoPerfil();
        $dt_nasc = DateUtil::ConvertDate($modelPerfil->getDataNascimentoPerfil(), DATEFORMAT::YMD, DATEFORMAT::DMY, "/", false);
        $idade = PanteonEscolarBaseModule::idade($modelPerfil->getDataNascimentoPerfil());

        $info_nasc = '<div id="subtitulos">Data Nascimento: </div>'.$dt_nasc.' ('.$idade.' anos)';

        if($modelPerfil->getCaminhoFotoPerfil() != "") {
          $span1->addXmlnukeObject(new XmlNukeText('<div id="caixa_info_perfil_foto"><img alt="Foto" width="100" height="100" src="'.$modelPerfil->getCaminhoFotoPerfil().'"/></div>'));
        } else {
          $span1->addXmlnukeObject(new XmlNukeText('<div id="caixa_info_perfil_foto"><img alt="Foto Padrão" title="Foto Padrão" src="/upload/Foto_Default.jpg"/></div>'));

        }

        $span1->addXmlnukeObject(new XmlNukeText('<div id="caixa_info_perfil_esquerda">'));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Nome Completo: </div>'.$modelUsuario->getNomeCompletoUsuario()));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Cidade: </div>'.$cidade));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="subtitulos">Estado: </div>'.$modelPerfil->getUFEstado()));
        $span1->addXmlnukeObject(new XmlNukeText($info_nasc));
        $span1->addXmlnukeObject(new XmlNukeText('</div>'));
        $span1->addXmlnukeObject(new XmlNukeText('<div id="caixa_info_perfil_principal">'));

        $span1->addXmlnukeObject(new XmlNukeText('<div id="link_barra_direita"> <a href="/meuperfil&acao=alterar&id='.$id_perfil.'">Alterar meu perfil</a> </div>'));
        $span1->addXmlnukeObject(new XmlNukeText('<br/><div id="subtitulos">Perfil Resumido: </div>'.$texto_perfil));
        $span1->addXmlnukeObject(new XmlNukeText('</div>'));

      } else if($this->_context->ContextValue("acao") == "") {
        $aviso = '<div id="meuPerfil">Seu Perfil ainda não foi cadastrado.<br/> <a href="/meuperfil&acao=ppnew"> Clique aqui para preenchê-lo</a></div>';
        $span1->addXmlnukeObject(new XmlNukeText($aviso));

      }

    }

    //
    // Fim - mostrarMeuPerfil

    // Inicio - listarDireita
    //
    if($this->_opcao == "listarDireita") {
      $node = XmlUtil::CreateChild($current, "blockmensagem", "");
      $body = PanteonEscolarBaseModule::criarTitulo($node);
      $body = PanteonEscolarBaseModule::preencherBarraVazia($node);

      if(($nivel_acesso =="GESTOR") || ($nivel_acesso =="ADMINISTRADOR") || ($nivel_acesso =="EDITOR"))
        XmlUtil::AddAttribute($node, "criartemapanteon", "true");

    }

    //
    // Fim - listarDireita

    // Inicio - menu
    //
    if($this->_opcao == "menu") {
      $node = XmlUtil::CreateChild($current, "blockabausuario", "");
      $body = PanteonEscolarBaseModule::preencherMenuPadrao($node, PanteonEscolarMenu::MeuPerfil);
    }
    //
    // Fim - menu

    // Inicio - menu head
    //
    if($this->_opcao == "menuHead") {
      $nodeHead = XmlUtil::CreateChild($current, "blockhead", "");
      XmlUtil::AddAttribute($nodeHead, "perfil", strtolower($nivel_acesso));

      $msg = "Bem-Vindo, ".ucfirst($this->_context->authenticatedUser())." (".$nivel_acesso.").";
      $node = XmlUtil::CreateChild($current, "blockbarramenu", "");
      $body = PanteonEscolarBaseModule::preencherMenuHead($node, PanteonEscolarBaseModule::preencherMenuHeadPadrao($nivel_acesso, 'meuperfil'));
      XmlUtil::AddAttribute($node, "nome_usuario", $msg);
      XmlUtil::AddAttribute($node, "logout", "true");

    }
    //
    // Fim - menu head

    $node = XmlUtil::CreateChild($current, "blockcenter", "");
    $body = XmlUtil::CreateChild($node, "body", "");

    parent::generatePage($body);

  }

  public function MeuPerfilDBXML($context, $opcao) {
    if(!($context instanceof Context)) throw new Exception("Falta de Context");

    $this->_context = $context;
    $this->_opcao = $opcao;

  }

}

?>
