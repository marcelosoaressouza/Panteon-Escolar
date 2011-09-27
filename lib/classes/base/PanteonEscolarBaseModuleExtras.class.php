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

class PanteonEscolarPasswordFormatter implements IEditListFormatter
{

  public function Format($row, $fieldname, $value)
  {
    return sha1($value);
  }

}

class PanteonEscolarDataFormatter implements IEditListFormatter
{

  public function Format($row, $fieldname, $value)
  {
    return DateUtil::ConvertDate($value, DATEFORMAT::YMD, DATEFORMAT::DMY, "/", false);
  }

}

class PanteonEscolarDataMySQLFormatter implements IEditListFormatter
{

  public function Format($row, $fieldname, $value)
  {
    return DateUtil::ConvertDate($value, DATEFORMAT::DMY, DATEFORMAT::YMD, "-", false);
  }

}

class PanteonEscolarTagFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarTagFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new TagDB($this->_context);
    $model = new TagModel();
    $tags = trim($value);
    $tags = explode(',', $tags);

    foreach($tags as $tag)
    {
      $tag = trim(strtolower($tag));
      $model->setNomeTag($tag);

      if($db->verDuplicado($tag) <= 0)
      {
        $db->cadastrar($model);
      }
    }

    return strtolower($value);
  }

}

class PanteonEscolarPesquisadorFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarPesquisadorFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new UsuarioDB($this->_context);
    $id_instituicao = $db->obterPorId($value)->getIDInstituicao();
    $db = new InstituicaoDB($this->_context);
    return $db->obterPorId($id_instituicao)->getNomeInstituicao();
  }

}

class PanteonEscolarPerfilPesquisadorFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_id_usuario;
  protected $_acao;

  public function PanteonEscolarPerfilPesquisadorFormatter($context, $acao = "", $id_usuario = "")
  {
    $this->_context = $context;
    $this->_id_usuario = $id_usuario;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {


    if($this->_acao == "view")
    {
      $db = new UsuarioDB($this->_context);
      $nome_usuario = $db->obterPorId($value)->getNomeCompletoUsuario();

      $url_link = $this->_context->bindModuleUrl("panteonescolar.meuperfil");
      $link = $url_link . '&amp;usuario=' . $value;
      $LinkPerfilSujeito = '<b><a href="' . $link . '">' . $nome_usuario . '</a></b>';
    }

    return $LinkPerfilSujeito;
  }



}

class PanteonEscolarUploadByIDFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarUploadByIDFormatter($context, $acao)
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {
    if($this->_acao == "url")
    {
      $db = new MidiatecaDB($this->_context);
      $arquivo = $db->obterPorId($value)->getURLMidiateca();

      if($arquivo != "")
      {
        $url = '<a target="_blank" href="http://' . $arquivo . '">Endereço Internet</a>';
      }

      else
      {
        $url = '';
      }
    }

    else
    {
      $db = new MidiatecaDB($this->_context);
      $arquivo = $db->obterPorId($value)->getCaminhoArquivoMidiateca();

      if($arquivo != "")
      {
        $url = PanteonEscolarUploadFormatter::Format(0, 0, $arquivo);
      }

      else
      {
        $url = '';
      }
    }

    return $url;
  }

}

class PanteonEscolarNomeUsuarioPorID implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarNomeUsuarioPorID($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new UsuarioDB($this->_context);

    return $db->obterPorId($value)->getNomeCompletoUsuario();
  }

}

class PanteonEscolarDestinatarioMensagemUsuario implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarDestinatarioMensagemUsuario($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new UsuarioDB($this->_context);
    $arrUsuarios = array();
    $arrUsuarios = explode(",", $value);

    foreach($arrUsuarios as $id_usuario)
    {
      $nome = $db->obterPorId($id_usuario)->getNomeCompletoUsuario();

      if(!empty($nome))
      {
        $arrNome[] = $nome;
      }
    }
    $strUsuario = implode("<br />", $arrNome);
    return $strUsuario;
  }

}

class PanteonEscolarTextoMensagemUsuario implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarTextoMensagemUsuario($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {

    $db_mensagem = new MensagemUsuarioDB($this->_context);
    $data_hora = $db_mensagem->obterPorId($row->getField("id_mensagem_usuario"))->getDataHoraCadastroMensagemUsuario();

    $data = DateUtil::ConvertDate($data_hora, DATEFORMAT::YMD,DATEFORMAT::DMY,"/",true);

    if(strlen($db_mensagem->obterPorId($row->getField("id_mensagem_usuario"))->getTextoMensagemUsuario())>100)
    {
      $texto = $db_mensagem->obterPorId($row->getField("id_mensagem_usuario"))->getTextoMensagemUsuario();
      $texto = str_replace(array("<p>","</p>"), "", $texto);
      $texto = substr($texto,0,100)."...";
    }

    else
    {
      $texto = $db_mensagem->obterPorId($row->getField("id_mensagem_usuario"))->getTextoMensagemUsuario();
    }

    $strMensagem = '<div id="MensagemUsuarioLinha">'.$texto."<br/><i>Postada em ".$data."</i></div>";
    return $strMensagem;
  }
}


class PanteonEscolarMensagemForumUsuario implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarMensagemForumUsuario($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new UsuarioDB($this->_context);

    $nome = $db->obterPorId($value)->getNomeCompletoUsuario();
    $usuario = $db->obterPorId($value)->getLoginUsuario();

    $perfildb = new PerfilDB($this->_context);
    $caminho_foto = $perfildb->obterPorId($value)->getCaminhoFotoPerfil();

    if(empty($caminho_foto))
    {
      $caminho_foto = "static/images/nenhuma_imagem.gif";
    }

    $strImagem = '<img src="' . $caminho_foto . '" title="' . $nome . '" />';

    $strUsuario = "<div id='ForumLinhaDadosUsuario'>(" . $usuario . ")<br />" . $nome . "<br />" . $strImagem . "</div>";

    return $strUsuario;
  }

}

/**
 * @author Roberto Rander rrander at gmail.com
 */
class PanteonEscolarMensagemForum implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarMensagemForum($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new MensagemForumDB($this->_context);
    $data = DateUtil::ConvertDate($db->obterPorId($value)->getDataHoraCadastroMensagemForum(), DATEFORMAT::YMD,DATEFORMAT::DMY,"/",true);
    $titulo = $db->obterPorId($value)->getNomeMensagemForum();
    $texto = $db->obterPorId($value)->getTextoMensagemForum();
    $id_mensagem_resposta = $db->obterPorId($value)->getIDMensagemResposta();

    $strMensagemOrigem = "";

    /* Verifica se a mensagem possui pai para carregar variaveis - se é resposta de alguma mensagem filha */
    if((intval($id_mensagem_resposta)>0) && ($id_mensagem_resposta != $this->_context->ContextValue("id_mensagem_forum")))
    {
      $dbusuario = new UsuarioDB($this->_context);
      $autor_mensagem_pai = $dbusuario->obterPorId($db->obterPorId($id_mensagem_resposta)->getIDUsuario())->getNomeCompletoUsuario();

      $data_mensagem_pai = DateUtil::ConvertDate($db->obterPorId($id_mensagem_resposta)->getDataHoraCadastroMensagemForum(), DATEFORMAT::YMD,DATEFORMAT::DMY,"/",true);
      $titulo_mensagem_pai = $db->obterPorId($id_mensagem_resposta)->getNomeMensagemForum();
      $texto_mensagem_pai = $db->obterPorId($id_mensagem_resposta)->getTextoMensagemForum();

      $strMensagemOrigem = 'Mensagem original:<div id="ForumMensagemPai"><b>'.$titulo_mensagem_pai."</b><hr/>".$texto_mensagem_pai."<br/><i>Postado em ".$data_mensagem_pai." por ".$autor_mensagem_pai.".</i></div><br />";
    }

    $strMensagem = '<div id="ForumLinha"><b>'.$titulo."</b><hr/>".$strMensagemOrigem.$texto."<br/><i>Postado em ".$data."</i></div>";

    return $strMensagem;

  }
}


/**
 * @author Roberto Rander rrander at gmail.com
 */
class PanteonEscolarMensagemTopicoForum implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarMensagemTopicoForum($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $data = "";
    $db = new MensagemForumDB($this->_context);
    $data = DateUtil::ConvertDate($db->obterPorId($value)->getDataHoraCadastroMensagemForum(), DATEFORMAT::YMD,DATEFORMAT::DMY,"/",true);
    $titulo = $db->obterPorId($value)->getNomeMensagemForum();
    $texto = $db->obterPorId($value)->getTextoMensagemForum();

    $url = "?module=panteonescolar.meuforummensagenstopico&amp;id_mensagem_forum=".$value."&amp;id_tema_panteon_definido=".$this->_context->getCookie("id_tema_panteon_definido");

    $strMensagem = '<div id="ForumLinha"><a href="'.$url.'" alt="Visualizar mensagens deste tópico" title="Visualizar mensagens deste tópico"><b>'.$titulo."</b></a><hr/>".$texto."<br/><i>Postado em ".$data."</i></div>";

    return $strMensagem;

  }
}


class PanteonEscolarUploadFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarUploadFormatter($context = "")
  {
    $this->_context = $context;

  }

  public function Format($row, $fieldname, $value)
  {
    $ext = pathinfo($value, PATHINFO_EXTENSION);

    if(($ext == "jpg") || ($ext == "JPG") ||
        ($ext == "jpeg") || ($ext == "JPEG") ||
        ($ext == "png") || ($ext == "PNG") ||
        ($ext == "gif") || ($ext == "GIF"))
    {

      $tam = "75";
      list($width, $height) = $imageInfo = @getimagesize($value);

      if($width > 100)
      {
        $tam = 100;
      }

      return '<a class="foto_detalhe" href="' . $value . '"><img alt="Imagem" width="' . $tam . '" src="' . $value . '"/> </a>';
    }

    else if(($ext == "flv") || ($ext == "FLV") || ($ext == "avi") || ($ext == "AVI"))
    {
      $arq = explode("/", $value);
      //$url_link = $this->_context->bindModuleUrl("panteonescolar.vervideo", "ver", "PanteonEscolar", "pt-br");
      //$value = "/panteonescolar-src/www/static/swf/video.flv";
      //$link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=video&amp;lang=pt-br&amp;tipo=Video&amp;arq=' . $arq[2];
      $link = 'xmlnuke.php?module=panteonescolar.vervideo&amp;site=PanteonEscolar&amp;xsl=video&amp;lang=pt-br&amp;tipo=Video&amp;arq=' . $arq[2];
      $url = '<b><a class="video_detalhe" href="' . $link . '"><img alt="Ver Video" src="static/images/cinema.gif"/></a></b>';
      //$url = '<b><a class="video_detalhe" href="' . $value . '"><img alt="Ver Video" src="static/images/cinema.gif"/></a></b>';

      return $url;
    }

    else
    {
      if($value != "")
      {
        $url = '<b><a href="' . $value . '">Descarregar Arquivo (Download)</a></b>';
      }

      else
      {
        $url = 'Nenhum Arquivo enviado';
      }

      return $url;
    }
  }

}

class PanteonEscolarUploadTipoMidiaFormatter implements IEditListFormatter
{

  public function Format($row, $fieldname, $value)
  {
    $ext = pathinfo($value, PATHINFO_EXTENSION);

    if(($ext == "jpg") || ($ext == "JPG") ||
        ($ext == "jpeg") || ($ext == "JPEG") ||
        ($ext == "png") || ($ext == "PNG") ||
        ($ext == "gif") || ($ext == "GIF"))
    {
      $tam = "48";
      list($width, $height) = $imageInfo = getimagesize($value);

      if($width > 100)
      {
        $tam = 48;
      }

      return '<a class="foto_detalhe" href="' . $value . '"><img alt="Imagem" width="' . $tam . '" src="' . $value . '"/> </a>';
    }

    else
    {
      $url = 'Nenhum Arquivo enviado';

      return $url;
    }
  }

}

class PanteonEscolarTextoFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;
  protected $_oque;
  protected $_onde;

  public function PanteonEscolarTextoFormatter($context = "", $acao = "", $oque = "", $onde = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
    $this->_oque = $oque;
    $this->_onde = $onde;
  }

  public function Format($row, $fieldname, $value)
  {
    $tam_max = 250;

    if($this->_acao == "")
    {
      if(strlen($value) > $tam_max)
      {
        $texto = strip_tags(substr($value, 0, $tam_max)) . "<div id='aviso_texto_longo'>(Continua)</div>";

        return $texto;
      }

      else
      {

        return $value;
      }
    }

    else
    {
      $db = new GeralDB($this->_context);
      $texto = $db->obter($this->_oque, $this->_onde, $value);

      $link = $this->_context->bindModuleUrl("panteonescolar.ver", "ver", "PanteonEscolar", "pt-br");
      $link .= '&amp;oque=' . $this->_oque;
      $link .= '&amp;onde=' . $this->_onde;
      $link .= '&amp;ver=' . $value;

      $url = strip_tags(substr($texto, 0, $tam_max)) . '<br/><b><a class="lista_direita_detalhe" href="' . $link . '">(Ver Mais)</a></b>';

      if(strlen($texto) > $tam_max)
      {
        $url = strip_tags(substr($texto, 0, $tam_max)) . '<br/><b><a class="lista_direita_detalhe" href="' . $link . '">(Ver Mais)</a></b>';
      }

      else
      {
        $url = $texto;
      }

      return $url;
    }
  }

}

class PanteonEscolarTextoFormatterMetodoAnalise implements IEditListFormatter
{

  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;
  protected $_oque;
  protected $_onde;

  public function PanteonEscolarTextoFormatterMetodoAnalise($context = "", $acao = "", $oque = "", $onde = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
    $this->_oque = $oque;
    $this->_onde = $onde;
  }

  public function Format($row, $fieldname, $value)
  {
    $tam_max = 250;

    if($this->_acao == "")
    {
      if(strlen($value) > $tam_max)
      {
        $texto = strip_tags(substr($value, 0, $tam_max)) . "<div id='aviso_texto_longo'>(Continua)</div>";

        return $texto;
      }

      else
      {

        return $value;
      }
    }

    else
    {
      $db = new GeralDB($this->_context);
      $texto = $db->obter($this->_oque, $this->_onde, $value);

      $link = $link = $this->_context->bindModuleUrl("panteonescolar.ver", "ver", "PanteonEscolar", "pt-br");
      $link .= '&amp;oque=' . $this->_oque;
      $link .= '&amp;onde=' . $this->_onde;
      $link .= '&amp;ver=' . $value;

      $url = strip_tags(substr($texto, 0, $tam_max)) . '<br/><b><a class="lista_direita_detalhe" href="' . $link . '">(Ver Mais)</a></b>';

      if(strlen($texto) > $tam_max)
      {
        $url = strip_tags(substr($texto, 0, $tam_max)) . '<br/><b><a class="lista_direita_detalhe" href="' . $link . '">(Ver Mais)</a></b>';
      }

      else
      {
        $url = $texto;
      }

      return $url;
    }
  }

}

class PanteonEscolarTituloURLFormatter implements IEditListFormatter
{

  public function Format($row, $fieldname, $value)
  {
    // $url = explode("://", $value);
    // if($url[0] == "http") $url[0] = '<a href="http://'.$url[1].'">Clique para Visitar Site Indicado</a>';
    $url = "";

    if($value != "")
    {
      $url = '<a target="_blank" href="http://' . $value . '">Clique para Visitar Site Indicado</a>';
    }

    return $url;
  }

}

class PanteonEscolarMinhaNotaFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;

  public function PanteonEscolarMinhaNotaFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {
    if($this->_acao == "")
    {
      if($value > 0)
      {
        $db = new TemaPanteonDB($this->_context);
        return $db->obterPorId($value)->getNomeTemaPanteon();
      }

      else
      {
        return "Geral";
      }
    }

    else if($this->_acao == "delete")
    {
      $url_link = $this->_context->bindModuleUrl("panteonescolar.apagar");
      $link = $url_link . '&amp;oque=nota&amp;returnurl=minhasnotas&amp;apagar=' . $value . '&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br';
      $url = '<b><a class="delecao" href="' . $link . '"> <img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';
      return $url;
    }
  }

}

class PanteonEscolarPerfilFormatter implements IEditListFormatter
{

  public function Format($row, $fieldname, $value)
  {
    $tam_max = 400;

    if(strlen($value) > $tam_max)
    {
      $texto = strip_tags(substr($value, 0, $tam_max));
      $aviso = "<div id='aviso_texto_longo'>(Continua)</div>";

      return $texto . $aviso;
    }

    else
    {
      return $value;
    }
  }

}

class PanteonEscolarPontoDeVistaSujeitoFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_id_tema_panteon;

  public function PanteonEscolarPontoDeVistaSujeitoFormatter($context)
  {
    $this->_context = $context;
    $this->_id_tema_panteon = $id_tema_panteon;
  }

  public function Format($row, $fieldname, $value)
  {

    $url = '<b><a href="' . PanteonEscolarBaseModule::curPageURL() . '&acao=verPontoDeVista&valueid=' . $value . '">
           <img alt="Ponto de Vista" title="Ponto de Vista" src="static/images/icones/icone_pontodevista.png"/></a></b>
           ';

    return $url;
  }

}

class PanteonEscolarPontoDeVistaFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_id_usuario;
  protected $_acao;
  protected $_pagina;

  public function PanteonEscolarPontoDeVistaFormatter($context, $acao = "", $id_usuario = "", $pagina = "")
  {
    $this->_context = $context;
    $this->_id_usuario = $id_usuario;
    $this->_acao = $acao;
    $this->_pagina = $pagina;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "coletar")
    {
      $db = new UsuarioXPontoDeVistaDB($this->_context);
      $coletado = $db->obterPorIDUsuarioXPontoDeVista($this->_id_usuario, $value);

      if($coletado->Count() > 0)
      {
        $sr = $coletado->moveNext();
        $url = '<b> </b>';

        if($sr->getField("coletado_usuario_x_ponto_de_vista") == 1)
        {
          $url = '<img alt="Ponto de Vista Coletado" title="Ponto de Vista Coletado" src="static/images/icones/icone_coletar-pb.png"/>';
        }
      }

      else
      {
        $url_link = $this->_context->bindModuleUrl("panteonescolar.vercoletar");
        $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=coletar&amp;lang=pt-br&amp;vercoletar=' . $value . '&amp;pagina=' . $this->_pagina;
        $url = '<b><a class="coletar" href="' . $link . '"><img alt="Coletar Ponto de Vista" title="Coletar Ponto de Vista" src="static/images/icones/icone_coletar.png"/></a></b>';

        // $url = '<b><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=coletarPontoDeVista&valueid='.$value.'"><img alt="Coletar Ponto de Vista" title="Coletar Ponto de Vista" src="static/images/icones/icone_coletar.png"/></a></b>';
      }
    }

    if($this->_acao == "descartar")
    {
      $db = new UsuarioXPontoDeVistaDB($this->_context);
      $coletado = $db->obterPorIDUsuarioXPontoDeVista($this->_id_usuario, $value);

      if($coletado->Count() > 0)
      {
        $sr = $coletado->moveNext();
        $url = '<b></b>';

        if($sr->getField("coletado_usuario_x_ponto_de_vista") == 0)
        {
          $url = '<img alt="Ponto de Vista Descartado" title="Ponto de Vista Descartado" src="static/images/icones/icone_descartar-pb.png"/>';
        }
      }

      else
      {
        $url_link = $this->_context->bindModuleUrl("panteonescolar.vercoletar");
        $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=coletar&amp;lang=pt-br&amp;verdescartar=' . $value;
        $url = '<b><a class="coletar" href="' . $link . '"><img alt="Descartar Ponto de Vista" title="Descartar Ponto de Vista" src="static/images/icones/icone_descartar.png"/></a></b>';
//        $url = '<b><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=coletarPontoDeVista&subacao=descartar&valueid='.$value.'"> <img alt="Coletar Ponto de Vista" title="Coletar Ponto de Vista" src="static/images/icones/icone_descartar.png"/></a></b>';
      }
    }

    if($this->_acao == "view")
    {
      $db = new PontoDeVistaDB($this->_context);
      $texto = $db->obterPorId($value)->getTextoPontodeVista();

      if(strlen($texto) > 125)
      {
        $texto = strip_tags(substr($texto, 0, 120));
        $aviso = "<div id='aviso_texto_longo'>(Leia mais...)</div>";
      }

      $texto = utf8_encode($texto);
      $url_link = $this->_context->bindModuleUrl("panteonescolar.verpontodevista");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;pontodevista=' . $value;
      $url = '' . $texto . '<b><a class="lista_direita_detalhe" href="' . $link . '">' . $aviso . '</a></b>';
    }

    if($this->_acao == "sujeito")
    {
      $db = new PontoDeVistaDB($this->_context);
      $id_sujeito = $db->obterPorId($value)->getIDSujeito();
      $db = new SujeitoDB($this->_context);
      $foto = $db->obterPorId($id_sujeito)->getCaminhoFotoSujeito();
      $nomeSujeito = $db->obterPorId($id_sujeito)->getNomeSujeito();

      $url_link = $this->_context->bindModuleUrl("panteonescolar.versujeito");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;versujeito=' . $id_sujeito;
      $url = '<a class="lista_direita_detalhe" href="' . $link . '"><img alt="Sujeito" title="Sujeito" src="' . $foto . '"/></a><br />' . $nomeSujeito . '';
    }

    if($this->_acao == "delete")
    {
      $url_link = $this->_context->bindModuleUrl("panteonescolar.verusuarioxpontodevista");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br&amp;verusuarioxpontodevista=' . $value;

      $url = '<b><a class="delecao" href="' . $link . '"><img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';

    }

    return $url;
  }

}

class PanteonEscolarTemaPanteonFormatter implements IEditListFormatter
{

  /**
   *
   * Context type
   */
  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;

  /**
   *
   * @param Context $context
   * @param string $acao
   */
  public function PanteonEscolarTemaPanteonFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_id_tema_panteon = $id_tema_panteon;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "")
    {
      $url = '<b><a href="' . PanteonEscolarBaseModule::curPageURL() . '&acao=setDefault&valueid=' . $value . '">
             <img alt="Definir Tema Panteon" title="Definir Tema Panteon" src="static/images/icones/icone_definirtemapanteon.png"/></a></b>';
    }

    else if($this->_acao == "coletarBiblioteca")
    {
      $url = '<b><a href="' . PanteonEscolarBaseModule::curPageURL() . '&acao=getTemaPanteon&valueid=' . $value . '">
             <img alt="Definir Tema Panteon" title="Definir Tema Panteon" src="static/images/icones/icone_coletarbiblioteca.png"></img></a></b>';
    }

    else if($this->_acao == "view")
    {
      $db = new TemaPanteonDB($this->_context);
      $texto = $db->obterPorId($value)->getDescricaoTemaPanteon();

      if(strlen($texto) > 215)
      {
        $texto = strip_tags(substr($texto, 0, 215));
        $aviso = "<div id='aviso_texto_longo'>(Leia mais...)</div>";
      }

      $url_link = $this->_context->bindModuleUrl("panteonescolar.vertemapanteon");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vertemapanteon=' . $value;
      $url = '' . $texto . '<b><a class="lista_direita_detalhe" href="' . $link . '">' . $aviso . '</a></b>';
    }

    else if($this->_acao == "apagarTemaPanteon")
    {
      $url_link = $this->_context->bindModuleUrl("panteonescolar.apagar");
      $link = $url_link . '&amp;oque=usuario_x_tema_panteon&amp;returnurl=meustemaspanteon&amp;apagar=' . $value . '&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br';
      $url = '<b><a class="delecao" href="' . $link . '"> <img alt ="Retirar" title="Retirar" src="static/images/icones/icone_remover.png"/></a></b>';
    }

    return $url;
  }

}

class PanteonEscolarApagarFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_modulo;
  protected $_entidade;

  public function PanteonEscolarApagarFormatter($context, $entidade, $modulo)
  {
    $this->_context = $context;
    $this->_modulo = $modulo;
    $this->_entidade = $entidade;
  }

  public function Format($row, $fieldname, $value)
  {
    $url_link = $this->_context->bindModuleUrl("panteonescolar.apagar", "apagar", "PanteonEscolar", "pt-br");
    $link = $url_link . '&amp;oque=' . $this->_entidade;
    $link .= '&amp;returnurl=' . $this->_modulo;
    $link .= '&amp;apagar=' . $value;


    $url = '<b><a class="delecao" href="' . $link . '">';
    $url .= '<img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';

    return $url;
  }

}

class PanteonEscolarApagarMensagemForumFormatter implements IEditListFormatter
{

  /**
   * @var Context
   */
  protected $_context;
  protected $_modulo;
  protected $_entidade;

  public function PanteonEscolarApagarMensagemForumFormatter($context, $entidade, $modulo)
  {
    $this->_context = $context;
    $this->_modulo = $modulo;
    $this->_entidade = $entidade;
  }

  /**
   * @param SingleRow $row
   * @param string $fieldname
   * @param string $value
   */
  public function Format($row, $fieldname, $value)
  {

    $id_usuario_logado = $this->_context->authenticatedUserId();
    $nivel_acesso = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $id_usuario_logado);

    //Exibir o botão de apagar para o usuario autor da mensagem ou pertencente aos grupos: ADMINISTRADOR, GESTOR, MEDIADOR
    if(($row->getField("id_usuario") == $id_usuario_logado) || (($nivel_acesso == "GESTOR") || ($nivel_acesso == "ADMINISTRADOR") || ($nivel_acesso == "EDITOR")))
    {
      $url_link = $this->_context->bindModuleUrl("panteonescolar.apagar", "apagar", "PanteonEscolar", "pt-br");
      $link = $url_link . '&amp;oque=' . $this->_entidade;
      $link .= '&amp;returnurl=' . $this->_modulo;
      $link .= '&amp;apagar=' . $value;


      $url = '<b><a class="delecao" href="' . $link . '">';
      $url .= '<img alt ="Excluir" title="Excluir" src="static/images/icones/icone_remover.png" /></a></b>';
    }

    else
    {
      $url = ''; //<img alt ="Desabilitado Excluir" title="Desabilitado Excluir" src="static/images/icones/icone_remover.png" />
    }

    return $url;
  }

}

class PanteonEscolarTemaPanteonXSujeitoFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;

  public function PanteonEscolarTemaPanteonXSujeitoFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_id_tema_panteon = $id_tema_panteon;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "view")
    {
      $dbSujeito = new SujeitoDB($this->_context);
      $quantos_sujeitos = $dbSujeito->obterTodosOsSujeitosPorIDTemaPanteon($value)->Count();
      $url_link = $this->_context->bindModuleUrl("panteonescolar.versujeito");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vertemapanteon=' . $value;
      $url = '<b><a class="lista_direita_detalhe" href="' . $link . '"> ' . $quantos_sujeitos . '</a></b>';
    }

    return $url;
  }

}

class PanteonEscolarTemaPanteonXSituacaoProblemaFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;

  public function PanteonEscolarTemaPanteonXSituacaoProblemaFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_id_tema_panteon = $id_tema_panteon;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "view")
    {
      $dbSituacaoProblema = new SituacaoProblemaDB($this->_context);
      $quantas_situacoes = $dbSituacaoProblema->obterTodasAsSituacoesProblemasPorIDTemaPanteon($value)->Count();

      $url_link = $this->_context->bindModuleUrl("panteonescolar.versituacaoproblema");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vertemapanteon=' . $value;
      $url = '<b><a class="lista_direita_detalhe" href="' . $link . '"> ' . $quantas_situacoes . '</a></b>';
    }

    return $url;
  }

}

class PanteonEscolarMetodoAnaliseFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarMetodoAnaliseFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "view")
    {
      $db = new MetodoAnaliseDB($this->_context);
      $nome_metodo_analise = $db->obterPorId($value)->getNomeMetodoAnalise();

      $url_link = $this->_context->bindModuleUrl("panteonescolar.vermetodoanalise");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vermetodoanalise=' . $value;
      $url = '<b><a class="lista_direita_detalhe" href="' . $link . '">' . $nome_metodo_analise . '</a></b>';
    }

    if($this->_acao == "descricao")
    {
      $tam_max = 250;

      if(strlen($value) > $tam_max)
      {
        //$link = './xmlnuke.php?module=panteonescolar.vermetodoanalise&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vermetodoanalise='.$value;
        //$url = '<b><a class="lista_direita_detalhe" href="'.$link.'">'.$nome_metodo_analise.'</a></b>';
        $texto = strip_tags(substr($value, 0, $tam_max)) . "<div id='aviso_texto_longo'>(Continua)</div>";

        return $texto;
      }

      else
      {

        return $value;
      }
    }



    return $url;
  }

}

class PanteonEscolarEstruturaSocialFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarEstruturaSocialFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "view")
    {
      $db = new EstruturaSocialDB($this->_context);
      $nome_estrutura_social = $db->obterPorId($value)->getNomeEstruturaSocial();
      $url_link = $this->_context->bindModuleUrl("panteonescolar.verestruturasocial");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;verestruturasocial=' . $value;
      $url = '<b><a class="lista_direita_detalhe" href="' . $link . '">' . $nome_estrutura_social . '</a></b>';
    }

    return $url;
  }

}

class PanteonEscolarSujeitoFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarSujeitoFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "view")
    {
      $db = new SujeitoDB($this->_context);
      $texto = $db->obterPorId($value)->getDescricaoSujeito();

      if(strlen($texto) > 215)
      {
        $texto = strip_tags(substr($texto, 0, 210));
        $aviso = "<div id='aviso_texto_longo'>(Leia mais...)</div>";
      }

      $url_link = $this->_context->bindModuleUrl("panteonescolar.versujeito");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;versujeito=' . $value;
      $url = '' . $texto . '<b><a class="lista_direita_detalhe" href="' . $link . '">' . $aviso . '</a></b>';
    }

    return $url;
  }

}

class PanteonEscolarGrupoFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarGrupoFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "view")
    {
      $db = new GrupoDB($this->_context);
      $grupo = $db->obterPorId($value)->getNomeGrupo();
      $url_link = $this->_context->bindModuleUrl("panteonescolar.vergrupo");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vergrupo=' . $value;
      $url = '<b><a class="lista_direita_detalhe" href="' . $link . '">' . $grupo . '</a></b>';
    }

    return $url;
  }

}

class PanteonEscolarTurmaFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarTurmaFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "view")
    {
      $db = new TurmaDB($this->_context);
      $turma = $db->obterPorId($value)->getNomeTurma();

      $url_link = $this->_context->bindModuleUrl("panteonescolar.verturma");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;verturma=' . $value;
      $url = '<b><a class="lista_direita_detalhe" href="' . $link . '">' . $turma . '</a></b>';
    }

    return $url;
  }

}

// Formata / Complementa Dados
class PanteonEscolarMidiatecaDescByIDFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarMidiatecaDescByIDFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new MidiatecaDB($this->_context);
    return $db->obterPorId($value)->getDescricaoMidiateca();
  }

}

class PanteonEscolarSujeitoDescByIDFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarSujeitoDescByIDFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new PontodeVistaDB($this->_context);
    $id_sujeito = $db->obterPorId($value)->getIDSujeito();
    $db = new SujeitoDB($this->_context);

    return $db->obterPorId($id_sujeito)->getNomeSujeito();
  }

}

class PanteonEscolarItemAnaliseByIDFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarItemAnaliseByIDFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new PontodeVistaDB($this->_context);
    $id_item_analise = $db->obterPorId($value)->getIDItemAnalise();
    $db = new ItemAnaliseDB($this->_context);

    return $db->obterPorId($id_item_analise)->getNomeItemAnalise();
  }

}

class PanteonEscolarSituacaoProblemaByIDFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarSituacaoProblemaByIDFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new PontodeVistaDB($this->_context);
    $id_situacao_problema = $db->obterPorId($value)->getIDSituacaoProblema();
    $db = new SituacaoProblemaDB($this->_context);

    return $db->obterPorId($id_situacao_problema)->getNomeSituacaoProblema();
  }

}

class PanteonEscolarGrupoXTemaPanteonFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarGrupoXTemaPanteonFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new GrupoDB($this->_context);
    $id_tema_panteon = $db->obterPorId($value)->getIDTemaPanteon();
    $db = new TemaPanteonDB($this->_context);

    return $db->obterPorId($id_tema_panteon)->getNomeTemaPanteon();
  }

}

class PanteonEscolarUsuarioFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarUsuarioFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "delete")
    {
      $url_link = $this->_context->bindModuleUrl("panteonescolar.verusuario");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br&amp;verusuario=' . $value;
      $url = '<b><a class="delecao" href="' . $link . '"><img title="Excluir Usuário" alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';

      if($value == 1)
      {
        $url = "Não é possível esta operação.";
      }
    }

    else if($this->_acao == 'niveldeacesso')
    {
      $url = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $value);
    }

    return $url;
  }

}

class PanteonEscolarUsuarioXTurmaFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarUsuarioXTurmaFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "delete")
    {
      $url_link = $this->_context->bindModuleUrl("panteonescolar.verusuarioxturma");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br&amp;verusuarioxturma=' . $value;
      $url = '<b><a class="delecao" href="' . $link . '">
             <img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';
    }

    return $url;
  }

}

class PanteonEscolarTagDeleteFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarTagDeleteFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "delete")
    {
      $url_link = $this->_context->bindModuleUrl("panteonescolar.vertag");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br&amp;vertag=' . $value;
      $url = '<b><a class="delecao" href="' . $link . '">
             <img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';
    }

    return $url;
  }

}

class PanteonEscolarDiagnosticoIndividualFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_acao;

  public function PanteonEscolarDiagnosticoIndividualFormatter($context, $acao = "")
  {
    $this->_context = $context;
    $this->_acao = $acao;
  }

  public function Format($row, $fieldname, $value)
  {

    if($this->_acao == "item_analise")
    {
      $db = new DiagnosticoIndividualDB($this->_context);
      $id_item_analise = $db->obterPorId($value)->getIDItemAnalise();
      $db = new ItemAnaliseDB($this->_context);

      $url = $db->obterPorId($id_item_analise)->getNomeItemAnalise();
    }

    else if($this->_acao == "situacao_problema")
    {
      $db = new DiagnosticoIndividualDB($this->_context);
      $id_situacao_problema = $db->obterPorId($value)->getIDSituacaoProblema();
      $db = new SituacaoProblemaDB($this->_context);

      $url = $db->obterPorId($id_situacao_problema)->getNomeSituacaoProblema();
    }

    return $url;
  }

}

class PanteonEscolarTipoMidiaFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarTipoMidiaFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new TipoMidiaDB($this->_context);
    $foto = $db->obterPorId($value)->getCaminhoFotoTipoMidia();
    $nome = $db->obterPorId($value)->getNomeTipoMidia();

    if($foto != "")
    {
      $url = '<img alt="' . $nome . '" longdesc="' . $nome . '" src="' . $foto . '"/>';
    }

    else
    {
      $url = $nome;
    }

    return $url;
  }

}

class PanteonEscolarPontoDeVistaMidiaTecaFormatter implements IEditListFormatter
{

  protected $_context;

  public function PanteonEscolarPontoDeVistaMidiaTecaFormatter($context)
  {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value)
  {
    $db = new MidiatecaXPontoDeVistaDB($this->_context);
    $it = $db->obterTodasAsMidiatecasPorIDPontoDeVista($row->getField('id_ponto_de_vista'));

    if($it->hasNext())
    {
      $row = $it->current();
      $foto = $row->getField('caminho_arquivo_midiateca');
      $link = $row->getField('url_midiateca');
      $nome = strip_tags($row->getField('descricao_midiateca'));
    }

    else
    {
      $foto = "";
    }

    //$db = new TipoMidiaDB($this->_context);
    //$foto = $db->obterPorId($value)->getCaminhoFotoTipoMidia();
    //$nome = $db->obterPorId($value)->getNomeTipoMidia();

    if($foto != "")
    {


      $ext = pathinfo($foto, PATHINFO_EXTENSION);

      if(($ext == "jpg") || ($ext == "JPG") ||
          ($ext == "jpeg") || ($ext == "JPEG") ||
          ($ext == "png") || ($ext == "PNG") ||
          ($ext == "gif") || ($ext == "GIF"))
      {

        $tam = "75";
        list($width, $height) = $imageInfo = getimagesize($value);

        if($width > 100)
        {
          $tam = 100;
        }

        return '<a class="foto_detalhe" href="' . $foto . '"><img alt="Imagem" width="' . $tam . '" src="' . $foto . '"/> </a>';
      }

      else if(($ext == "flv") || ($ext == "FLV"))
      {
        $arq = explode("/", $foto);
        $url_link = $this->_context->bindModuleUrl("panteonescolar.vervideo");
        $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=video&amp;lang=pt-br&amp;tipo=Video&amp;arq=' . $arq[2];
        $url = '<b><a class="video_detalhe" href="' . $link . '"><img alt="Ver Video" src="static/images/cinema.gif"/></a></b>';

        return $url;
      }

      else
      {
        if($value != "")
        {
          $url = '<b><a href="' . $foto . '">Descarregar Arquivo (Download)</a></b>';
        }

        else
        {
          $url = 'Nenhum Arquivo enviado';
        }
      }


    }

    elseif(!empty($link))
    {
      return '<a class="youtube" href="' . $link . '"><img alt="Imagem" width="' . $tam . '" src="static/images/cinema.gif"/> </a>';
    }

    else
    {
      $url = "Nenhum Arquivo enviado";
    }

    return $url;
  }

}

class PanteonEscolarUploadFormatter2 implements IEditListFormatter
{

  public function Format($row, $fieldname, $value)
  {
    $ext = pathinfo($value, PATHINFO_EXTENSION);

    if(($ext == "jpg") || ($ext == "JPG") ||
        ($ext == "jpeg") || ($ext == "JPEG") ||
        ($ext == "png") || ($ext == "PNG") ||
        ($ext == "gif") || ($ext == "GIF"))
    {

      $tam = "75";
      list($width, $height) = $imageInfo = getimagesize($value);

      if($width > 100)
      {
        $tam = 100;
      }

      return '<a class="foto_detalhe" href="' . $value . '"><img alt="Imagem" width="' . $tam . '" src="' . $value . '"/> </a>';
    }

    else if(($ext == "flv") || ($ext == "FLV"))
    {
      $arq = explode("/", $value);
      $url_link = $this->_context->bindModuleUrl("panteonescolar.vervideo");
      $link = $url_link . '&amp;site=PanteonEscolar&amp;xsl=video&amp;lang=pt-br&amp;tipo=Video&amp;arq=' . $arq[2];
      $url = '<b><a class="video_detalhe" href="' . $link . '"><img alt="Ver Video" src="static/images/cinema.gif"/></a></b>';

      return $url;
    }

    else
    {
      if($value != "")
      {
        $url = '<b><a href="' . $value . '">Descarregar Arquivo (Download)</a></b>';
      }

      else
      {
        $url = 'Nenhum Arquivo enviado';
      }

      return $url;
    }
  }

}

?>
