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
  public function Format($row, $fieldname, $value) {
    return sha1($value);
  }
}

class PanteonEscolarDataFormatter implements IEditListFormatter
{
  public function Format($row, $fieldname, $value) {
    return DateUtil::ConvertDate($value, DATEFORMAT::YMD, DATEFORMAT::DMY, "/", false);
  }
}

class PanteonEscolarDataMySQLFormatter implements IEditListFormatter
{
  public function Format($row, $fieldname, $value) {
    return DateUtil::ConvertDate($value, DATEFORMAT::DMY, DATEFORMAT::YMD, "-", false);
  }
}

class PanteonEscolarTagFormatter implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarTagFormatter($context) {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value) {
    $db = new TagDB($this->_context);
    $model = new TagModel();
    $tags = trim($value);
    $tags = explode(',', $tags);

    foreach($tags as $tag) {
      $tag = trim(strtolower($tag));
      $model->setNomeTag($tag);
      if($db->verDuplicado($tag) <= 0) $db->cadastrar($model);
    }

    return strtolower($value);
  }
}

class PanteonEscolarPesquisadorFormatter implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarPesquisadorFormatter($context) {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value) {
    $db = new UsuarioDB($this->_context);
    $id_instituicao = $db->obterPorId($value)->getIDInstituicao();
    $db = new InstituicaoDB($this->_context);
    return $db->obterPorId($id_instituicao)->getNomeInstituicao();
  }
}

class PanteonEscolarUploadByIDFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarUploadByIDFormatter($context, $acao) {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {
    if($this->_acao == "url") {
      $db = new MidiatecaDB($this->_context);
      $arquivo = $db->obterPorId($value)->getURLMidiateca();

      if($arquivo != "") {
        $url = '<a target="_blank" href="http://'.$arquivo.'">Endereço Internet</a>';
      } else {
        $url = '';
      }

    } else {
      $db = new MidiatecaDB($this->_context);
      $arquivo = $db->obterPorId($value)->getCaminhoArquivoMidiateca();
      if($arquivo != "") {
        $url = PanteonEscolarUploadFormatter::Format(0, 0, $arquivo);
      } else {
        $url = '';
      }

    }

    return $url;

  }
}

class PanteonEscolarNomeUsuarioPorID implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarNomeUsuarioPorID($context) {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value) {
    $db = new UsuarioDB($this->_context);

    return $db->obterPorId($value)->getNomeCompletoUsuario();

  }
}

class PanteonEscolarUploadFormatter implements IEditListFormatter
{
  public function Format($row, $fieldname, $value) {
    $ext = pathinfo($value, PATHINFO_EXTENSION);

    if(($ext == "jpg") || ($ext == "JPG")   ||
        ($ext == "jpeg") || ($ext == "JPEG") ||
        ($ext == "png") || ($ext == "PNG")   ||
        ($ext == "gif") || ($ext == "GIF")) {

      $tam = "75";
      list($width, $height) = $imageInfo = getimagesize($value);

      if($width > 100) $tam = 100;

      return '<a class="foto_detalhe" href="'.$value.'"><img alt="Imagem" width="'.$tam.'" src="'.$value.'"/> </a>';

    } else if(($ext == "flv") || ($ext == "FLV")) {
      $arq = explode("/", $value);
      $link = '/xmlnuke.php?module=panteonescolar.vervideo&amp;site=PanteonEscolar&amp;xsl=video&amp;lang=pt-br&amp;tipo=Video&amp;arq='.$arq[2];
      $url = '<b><a class="video_detalhe" href="'.$link.'"><img alt="Ver Video" src="static/images/cinema.gif"/></a></b>';

      return $url;

    } else  {
      if($value != "")
        $url = '<b><a href="'.$value.'">Descarregar Arquivo (Download)</a></b>';
      else
        $url = 'Nenhum Arquivo enviado';

      return $url;

    }
  }
}

class PanteonEscolarUploadTipoMidiaFormatter implements IEditListFormatter
{
  public function Format($row, $fieldname, $value) {
    $ext = pathinfo($value, PATHINFO_EXTENSION);

    if(($ext == "jpg") || ($ext == "JPG")   ||
        ($ext == "jpeg") || ($ext == "JPEG") ||
        ($ext == "png") || ($ext == "PNG")   ||
        ($ext == "gif") || ($ext == "GIF")) {
      $tam = "48";
      list($width, $height) = $imageInfo = getimagesize($value);

      if($width > 100) $tam = 48;

      return '<a class="foto_detalhe" href="'.$value.'"><img alt="Imagem" width="'.$tam.'" src="'.$value.'"/> </a>';

    } else  {
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

  public function PanteonEscolarTextoFormatter($context = "", $acao = "", $oque = "", $onde = "") {
    $this->_context = $context;
    $this->_acao = $acao;
    $this->_oque = $oque;
    $this->_onde = $onde;

  }

  public function Format($row, $fieldname, $value) {
    $tam_max = 250;

    if($this->_acao == "") {
      if(strlen($value) > $tam_max) {
        $texto =  strip_tags(substr($value, 0 , $tam_max))."<div id='aviso_texto_longo'>(Continua)</div>";

        return $texto;
      } else {

        return $value;
      }

    } else {
      $db = new GeralDB($this->_context);
      $texto = $db->obter($this->_oque, $this->_onde, $value);

      $link  = '/xmlnuke.php?module=panteonescolar.ver';
      $link .= '&amp;oque='.$this->_oque;
      $link .= '&amp;onde='.$this->_onde;
      $link .= '&amp;ver='.$value;
      $link .= '&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br';
      $url = strip_tags(substr($texto, 0, $tam_max)).'<br/><b><a class="lista_direita_detalhe" href="'.$link.'">(Ver Mais)</a></b>';

      if(strlen($texto) > $tam_max)
        $url = strip_tags(substr($texto, 0, $tam_max)).'<br/><b><a class="lista_direita_detalhe" href="'.$link.'">(Ver Mais)</a></b>';
      else
        $url = $texto;

      return $url;

    }

  }

}

class PanteonEscolarTituloURLFormatter implements IEditListFormatter
{
  public function Format($row, $fieldname, $value) {
    // $url = explode("://", $value);
    // if($url[0] == "http") $url[0] = '<a href="http://'.$url[1].'">Clique para Visitar Site Indicado</a>';
    $url = "";
    if($value != "")  $url = '<a target="_blank" href="http://'.$value.'">Clique para Visitar Site Indicado</a>';
    return $url;

  }
}

class PanteonEscolarMinhaNotaFormatter implements IEditListFormatter
{

  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;

  public function PanteonEscolarMinhaNotaFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {
    if($this->_acao == "") {
      if($value > 0) {
        $db = new TemaPanteonDB($this->_context);
        return $db->obterPorId($value)->getNomeTemaPanteon();
      } else
        return "---";
    } else if($this->_acao == "delete") {
      $link = '/xmlnuke.php?module=panteonescolar.apagar&amp;oque=nota&amp;returnurl=minhasnotas&amp;apagar='.$value.'&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br';
      $url = '<b><a class="delecao" href="'.$link.'"> <img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';
      return $url;
    }

  }
}

class PanteonEscolarPerfilFormatter implements IEditListFormatter
{
  public function Format($row, $fieldname, $value) {
    $tam_max = 400;

    if(strlen($value) > $tam_max) {
      $texto = strip_tags(substr($value, 0 , $tam_max));
      $aviso = "<div id='aviso_texto_longo'>(Continua)</div>";

      return $texto.$aviso;

    } else {
      return $value;

    }

  }
}

class PanteonEscolarPontoDeVistaSujeitoFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_id_tema_panteon;

  public function PanteonEscolarPontoDeVistaSujeitoFormatter($context) {
    $this->_context = $context;
    $this->_id_tema_panteon = $id_tema_panteon;

  }

  public function Format($row, $fieldname, $value) {

    $url = '<b><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=verPontoDeVista&valueid='.$value.'">
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

  public function PanteonEscolarPontoDeVistaFormatter($context, $acao = "", $id_usuario = "") {
    $this->_context = $context;
    $this->_id_usuario = $id_usuario;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "coletar")  {
      $db = new UsuarioXPontoDeVistaDB($this->_context);
      $coletado = $db->obterPorIDUsuarioXPontoDeVista($this->_id_usuario, $value);

      if($coletado->Count() > 0) {
        $sr = $coletado->moveNext();
        $url = '<b> </b>';
        if($sr->getField("coletado_usuario_x_ponto_de_vista") == 1) $url = '<img alt="Ponto de Vista Coletado" title="Ponto de Vista Coletado" src="static/images/icones/icone_coletar-pb.png"/>';

      } else {
        $link = '/xmlnuke.php?module=panteonescolar.vercoletar&amp;site=PanteonEscolar&amp;xsl=coletar&amp;lang=pt-br&amp;vercoletar='.$value;
        $url = '<b><a class="coletar" href="'.$link.'"><img alt="Coletar Ponto de Vista" title="Coletar Ponto de Vista" src="static/images/icones/icone_coletar.png"/></a></b>';

        // $url = '<b><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=coletarPontoDeVista&valueid='.$value.'"><img alt="Coletar Ponto de Vista" title="Coletar Ponto de Vista" src="static/images/icones/icone_coletar.png"/></a></b>';

      }

    }

    if($this->_acao == "descartar")  {
      $db = new UsuarioXPontoDeVistaDB($this->_context);
      $coletado = $db->obterPorIDUsuarioXPontoDeVista($this->_id_usuario, $value);

      if($coletado->Count() > 0) {
        $sr = $coletado->moveNext();
        $url = '<b></b>';
        if($sr->getField("coletado_usuario_x_ponto_de_vista") == 0) $url = '<img alt="Ponto de Vista Descartado" title="Ponto de Vista Descartado" src="static/images/icones/icone_descartar-pb.png"/>';

      } else {
        $link = '/xmlnuke.php?module=panteonescolar.vercoletar&amp;site=PanteonEscolar&amp;xsl=coletar&amp;lang=pt-br&amp;verdescartar='.$value;
        $url = '<b><a class="coletar" href="'.$link.'"><img alt="Descartar Ponto de Vista" title="Descartar Ponto de Vista" src="static/images/icones/icone_descartar.png"/></a></b>';
//        $url = '<b><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=coletarPontoDeVista&subacao=descartar&valueid='.$value.'"> <img alt="Coletar Ponto de Vista" title="Coletar Ponto de Vista" src="static/images/icones/icone_descartar.png"/></a></b>';

      }

    }

    if($this->_acao == "view") {
      $db = new PontoDeVistaDB($this->_context);
      $texto = $db->obterPorId($value)->getTextoPontodeVista();

      if(strlen($texto) > 125) {
        $texto =  strip_tags(substr($texto, 0 , 120));
        $aviso = "<div id='aviso_texto_longo'>(Leia mais...)</div>";
      }

      $texto = htmlentities($texto, ENT_QUOTES, 'UTF-8');

      $link = '/xmlnuke.php?module=panteonescolar.verpontodevista&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;pontodevista='.$value;
      $url = ''.$texto.'<b><a class="lista_direita_detalhe" href="'.$link.'">'.$aviso.'</a></b>';

    }

    if($this->_acao == "sujeito") {
      $db = new PontoDeVistaDB($this->_context);
      $id_sujeito = $db->obterPorId($value)->getIDSujeito();
      $db = new SujeitoDB($this->_context);
      $foto = $db->obterPorId($id_sujeito)->getCaminhoFotoSujeito();

      $link = '/xmlnuke.php?module=panteonescolar.versujeito&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;versujeito='.$id_sujeito;
      $url = '<a class="lista_direita_detalhe" href="'.$link.'"><img alt="Sujeito" title="Sujeito" src="'.$foto.'"/></a>';

    }

    if($this->_acao == "delete") {
      $link = '/xmlnuke.php?module=panteonescolar.verusuarioxpontodevista&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br&amp;verusuarioxpontodevista='.$value;
      $url = '<b><a class="delecao" href="'.$link.'"><img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';

    }

    return $url;

  }

}

class PanteonEscolarTemaPanteonFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;

  public function PanteonEscolarTemaPanteonFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_id_tema_panteon = $id_tema_panteon;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "")  {
      $url = '<b><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=setDefault&valueid='.$value.'">
             <img alt="Definir Tema Panteon" title="Definir Tema Panteon" src="static/images/icones/icone_definirtemapanteon.png"/></a></b>';

    } else if($this->_acao == "coletarBiblioteca")  {
      $url = '<b><a href="'.PanteonEscolarBaseModule::curPageURL().'&acao=getTemaPanteon&valueid='.$value.'">
             <img alt="Definir Tema Panteon" title="Definir Tema Panteon" src="static/images/icones/icone_coletarbiblioteca.png"></img></a></b>';

    } else if($this->_acao == "view") {
      $db = new TemaPanteonDB($this->_context);
      $texto = $db->obterPorId($value)->getDescricaoTemaPanteon();

      if(strlen($texto) > 215) {
        $texto =  strip_tags(substr($texto, 0 , 215));
        $aviso = "<div id='aviso_texto_longo'>(Leia mais...)</div>";
      }

      $link = '/xmlnuke.php?module=panteonescolar.vertemapanteon&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vertemapanteon='.$value;
      $url = ''.$texto.'<b><a class="lista_direita_detalhe" href="'.$link.'">'.$aviso.'</a></b>';

    } else if($this->_acao == "apagarTemaPanteon")  {
      $link = '/xmlnuke.php?module=panteonescolar.apagar&amp;oque=usuario_x_tema_panteon&amp;returnurl=meustemaspanteon&amp;apagar='.$value.'&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br';
      $url = '<b><a class="delecao" href="'.$link.'"> <img alt ="Retirar" title="Retirar" src="static/images/icones/icone_remover.png"/></a></b>';

    }

    return $url;

  }

}

class PanteonEscolarApagarFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_modulo;
  protected $_entidade;

  public function PanteonEscolarApagarFormatter($context, $entidade, $modulo) {
    $this->_context = $context;
    $this->_modulo = $modulo;
    $this->_entidade = $entidade;

  }

  public function Format($row, $fieldname, $value) {

    $link  = '/xmlnuke.php?module=panteonescolar.apagar&amp;oque='.$this->_entidade;
    $link .= '&amp;returnurl='.$this->_modulo;
    $link .= '&amp;apagar='.$value;
    $link .= '&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br';

    $url  = '<b><a class="delecao" href="'.$link.'">';
    $url .= '<img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';

    return $url;

  }

}

class PanteonEscolarTemaPanteonXSujeitoFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;

  public function PanteonEscolarTemaPanteonXSujeitoFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_id_tema_panteon = $id_tema_panteon;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "view") {
      $dbSujeito = new SujeitoDB($this->_context);
      $quantos_sujeitos = $dbSujeito->obterTodosOsSujeitosPorIDTemaPanteon($value)->Count();

      $link = '/xmlnuke.php?module=panteonescolar.versujeito&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vertemapanteon='.$value;
      $url = '<b><a class="lista_direita_detalhe" href="'.$link.'"> '.$quantos_sujeitos.'</a></b>';

    }

    return $url;

  }

}

class PanteonEscolarTemaPanteonXSituacaoProblemaFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_id_tema_panteon;
  protected $_acao;

  public function PanteonEscolarTemaPanteonXSituacaoProblemaFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_id_tema_panteon = $id_tema_panteon;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "view") {
      $dbSituacaoProblema = new SituacaoProblemaDB($this->_context);
      $quantas_situacoes = $dbSituacaoProblema->obterTodasAsSituacoesProblemasPorIDTemaPanteon($value)->Count();

      $link = '/xmlnuke.php?module=panteonescolar.versituacaoproblema&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vertemapanteon='.$value;
      $url = '<b><a class="lista_direita_detalhe" href="'.$link.'"> ' . $quantas_situacoes . '</a></b>';

    }

    return $url;

  }

}

class PanteonEscolarMetodoAnaliseFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarMetodoAnaliseFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "view") {
      $db = new MetodoAnaliseDB($this->_context);
      $nome_metodo_analise = $db->obterPorId($value)->getNomeMetodoAnalise();

      $link = '/xmlnuke.php?module=panteonescolar.vermetodoanalise&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vermetodoanalise='.$value;
      $url = '<b><a class="lista_direita_detalhe" href="'.$link.'">'.$nome_metodo_analise.'</a></b>';
    }

    return $url;

  }

}

class PanteonEscolarEstruturaSocialFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarEstruturaSocialFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "view") {
      $db = new EstruturaSocialDB($this->_context);
      $nome_estrutura_social = $db->obterPorId($value)->getNomeEstruturaSocial();

      $link = '/xmlnuke.php?module=panteonescolar.verestruturasocial&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;verestruturasocial='.$value;
      $url = '<b><a class="lista_direita_detalhe" href="'.$link.'">'.$nome_estrutura_social.'</a></b>';
    }

    return $url;

  }

}

class PanteonEscolarSujeitoFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarSujeitoFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "view") {
      $db = new SujeitoDB($this->_context);
      $texto = $db->obterPorId($value)->getDescricaoSujeito();

      if(strlen($texto) > 215) {
        $texto =  strip_tags(substr($texto, 0 , 210));
        $aviso = "<div id='aviso_texto_longo'>(Leia mais...)</div>";
      }

      $link = '/xmlnuke.php?module=panteonescolar.versujeito&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;versujeito='.$value;
      $url = ''.$texto.'<b><a class="lista_direita_detalhe" href="'.$link.'">'.$aviso.'</a></b>';
    }

    return $url;

  }

}

class PanteonEscolarGrupoFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarGrupoFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "view") {
      $db = new GrupoDB($this->_context);
      $grupo = $db->obterPorId($value)->getNomeGrupo();

      $link = '/xmlnuke.php?module=panteonescolar.vergrupo&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vergrupo='.$value;
      $url = '<b><a class="lista_direita_detalhe" href="'.$link.'">'.$grupo.'</a></b>';
    }

    return $url;

  }

}

class PanteonEscolarTurmaFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarTurmaFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "view") {
      $db = new TurmaDB($this->_context);
      $turma = $db->obterPorId($value)->getNomeTurma();
      $link = '/xmlnuke.php?module=panteonescolar.verturma&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;verturma='.$value;
      $url = '<b><a class="lista_direita_detalhe" href="'.$link.'">'.$turma.'</a></b>';
    }

    return $url;

  }

}

// Formata / Complementa Dados
class PanteonEscolarMidiatecaDescByIDFormatter implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarMidiatecaDescByIDFormatter($context) {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value) {
    $db = new MidiatecaDB($this->_context);
    return $db->obterPorId($value)->getDescricaoMidiateca();

  }
}

class PanteonEscolarSujeitoDescByIDFormatter implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarSujeitoDescByIDFormatter($context) {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value) {
    $db = new PontodeVistaDB($this->_context);
    $id_sujeito = $db->obterPorId($value)->getIDSujeito();
    $db = new SujeitoDB($this->_context);

    return $db->obterPorId($id_sujeito)->getNomeSujeito();
  }
}

class PanteonEscolarItemAnaliseByIDFormatter implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarItemAnaliseByIDFormatter($context) {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value) {
    $db = new PontodeVistaDB($this->_context);
    $id_item_analise = $db->obterPorId($value)->getIDItemAnalise();
    $db = new ItemAnaliseDB($this->_context);

    return $db->obterPorId($id_item_analise)->getNomeItemAnalise();

  }
}

class PanteonEscolarSituacaoProblemaByIDFormatter implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarSituacaoProblemaByIDFormatter($context) {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value) {
    $db = new PontodeVistaDB($this->_context);
    $id_situacao_problema = $db->obterPorId($value)->getIDSituacaoProblema();
    $db = new SituacaoProblemaDB($this->_context);

    return $db->obterPorId($id_situacao_problema)->getNomeSituacaoProblema();
  }
}

class PanteonEscolarGrupoXTemaPanteonFormatter implements IEditListFormatter
{
  protected $_context;

  public function PanteonEscolarGrupoXTemaPanteonFormatter($context) {
    $this->_context = $context;
  }

  public function Format($row, $fieldname, $value) {
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

  public function PanteonEscolarUsuarioFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "delete")  {
      $link = '/xmlnuke.php?module=panteonescolar.verusuario&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br&amp;verusuario='.$value;
      $url = '<b><a class="delecao" href="'.$link.'"><img title="Excluir Usuário" alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';

      if($value == 1) $url = "Não é possível esta operação.";

    } else if($this->_acao == 'niveldeacesso') {
      $url = PanteonEscolarBaseModule::getNivelAcesso($this->_context, $value);
    }

    return $url;

  }

}

class PanteonEscolarUsuarioXTurmaFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarUsuarioXTurmaFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "delete")  {
      $link = '/xmlnuke.php?module=panteonescolar.verusuarioxturma&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br&amp;verusuarioxturma='.$value;
      $url = '<b><a class="delecao" href="'.$link.'">
             <img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';

    }

    return $url;

  }

}

class PanteonEscolarTagDeleteFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarTagDeleteFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "delete")  {
      $link = '/xmlnuke.php?module=panteonescolar.vertag&amp;site=PanteonEscolar&amp;xsl=apagar&amp;lang=pt-br&amp;vertag='.$value;
      $url = '<b><a class="delecao" href="'.$link.'">
             <img alt ="Excluir" src="static/images/icones/icone_remover.png"/></a></b>';

    }

    return $url;

  }

}

class PanteonEscolarDiagnosticoIndividualFormatter implements IEditListFormatter
{
  protected $_context;
  protected $_acao;

  public function PanteonEscolarDiagnosticoIndividualFormatter($context, $acao = "") {
    $this->_context = $context;
    $this->_acao = $acao;

  }

  public function Format($row, $fieldname, $value) {

    if($this->_acao == "item_analise")  {
      $db = new DiagnosticoIndividualDB($this->_context);
      $id_item_analise = $db->obterPorId($value)->getIDItemAnalise();
      $db = new ItemAnaliseDB($this->_context);

      $url = $db->obterPorId($id_item_analise)->getNomeItemAnalise();
    } else if($this->_acao == "situacao_problema")  {
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

  public function PanteonEscolarTipoMidiaFormatter($context) {
    $this->_context = $context;

  }

  public function Format($row, $fieldname, $value) {
    $db = new TipoMidiaDB($this->_context);
    $foto = $db->obterPorId($value)->getCaminhoFotoTipoMidia();
    $nome = $db->obterPorId($value)->getNomeTipoMidia();

    if($foto != "")
      $url = '<img alt="'.$nome.'" longdesc="'.$nome.'" src="'.$foto.'"/>';
    else
      $url = $nome;

    return $url;

  }

}

?>
