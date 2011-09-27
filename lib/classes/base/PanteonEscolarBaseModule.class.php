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

class PanteonEscolarMenu
{
  const Anotacoes = 'static/images/Layout/MenuUsuario/Anotacoes.png';
  const Grupos = 'static/images/Layout/MenuUsuario/Grupos.png';
  const MeuPerfil = 'static/images/Layout/MenuUsuario/meuPerfil.png';
  const Mural = 'static/images/Layout/MenuUsuario/Mural.png';
  const Turmas = 'static/images/Layout/MenuUsuario/Turmas.png';
  const Midiateca = 'static/images/Layout/MenuUsuario/Midiateca.png';
  const PontoDeVista = 'static/images/Layout/MenuUsuario/PontoDeVista.png';
  const Diagnostico = 'static/images/Layout/MenuUsuario/Diagnostico.png';
  const PlanoDeAcao = 'static/images/Layout/MenuUsuario/PlanoDeAcao.png';
  const MetodoDeAnalise = 'static/images/Layout/MenuUsuario/MetodoDeAnalise.png';
  const EstruturaSocial = 'static/images/Layout/MenuUsuario/EstruturaSocial.png';
  const CriarTema = 'static/images/Layout/MenuUsuario/CriarTemaPanteon.png';
  const SituacaoProblema = 'static/images/Layout/MenuUsuario/SituacaoProblema.png';
  const Forum = 'static/images/Layout/MenuUsuario/Forum.png';
  const Instituicao = 'static/images/Layout/MenuUsuario/Instituicao.png';
  const Usuario = 'static/images/Layout/MenuUsuario/Usuario.png';
  const TipoMidia = 'static/images/Layout/MenuUsuario/TipoMidia.png';
  const Tag = 'static/images/Layout/MenuUsuario/Tag.png';
  const MeusTemas = 'static/images/Layout/MenuUsuario/MeusTemas.png';
  const Biblioteca = 'static/images/Layout/MenuUsuario/Biblioteca.png';
  const TemaPanteon = 'static/images/Layout/MenuUsuario/MeusTemas.png';
}

class PanteonEscolarConsts
{
  const PontoDeVista_URL = './xmlnuke.php?module=panteonescolar.verpontodevista&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;ReturnUrl=%2fmeusdiagnosticos&amp;pontodevista=';
  const Contato = 'tarcisio.saraujo@gmail.com';
  const NomeRemetente = "Panteon Escolar";
  const EmailRemetente = "panteon@alfabetizacao.kinghost.net";
  const TextoRodapeEmailMensagemUsuario = "<br/><br/>Esta mensagem foi enviada através do Sistema Panteon Escolar.";
  const ComplementoAssuntoMensagemUsuario = "Mensagem de Usuário - ";
}

// Base Geral
ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseDBAccess.class.php");

// Classes Extras
ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseModuleExtras.class.php");

//Model
ModuleFactory::IncludePhp("panteonescolar", "classes/model/MuralModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/UsuarioModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/PropostaDeAcaoGrupoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/DiagnosticoGrupoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/DiagnosticoIndividualModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/DiagnosticoGeralModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/EstruturaSocialModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/GrupoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/GrupoSocialModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/InstituicaoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/EstadoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/ItemAnaliseModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/MensagemForumModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/MensagemUsuarioModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/MetodoAnaliseModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/MidiatecaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/NivelAcessoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/NotaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/PerfilModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/PermissaoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/PontodeVistaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/SituacaoProblemaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/SujeitoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/TagModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/TemaPanteonModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/TipoMidiaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/TurmaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/UsuarioModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/UsuarioXGrupoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/UsuarioXTurmaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/UsuarioXPontoDeVistaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/UsuarioXTemaPanteonModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/TemaPanteonXMidiatecaModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/TemaPanteonXTagModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/ForumModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/UsuarioXMensagemForumModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/PropostaDeAcaoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/PropostaDeAcaoGeralModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/UsuarioXNivelAcessoModel.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/model/MidiatecaXPontoDeVistaModel.class.php");

// DB Includes
ModuleFactory::IncludePhp("panteonescolar", "classes/db/MuralDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/UsuarioDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/PropostaDeAcaoGrupoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/DiagnosticoGrupoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/DiagnosticoIndividualDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/DiagnosticoGeralDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/EstruturaSocialDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/GrupoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/GrupoSocialDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/InstituicaoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/EstadoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/ItemAnaliseDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/MensagemForumDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/MensagemUsuarioDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/MetodoAnaliseDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/MidiatecaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/NivelAcessoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/NotaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/PerfilDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/PermissaoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/PontodeVistaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/SituacaoProblemaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/SujeitoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/TagDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/TemaPanteonDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/TipoMidiaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/TurmaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/UsuarioDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/UsuarioXGrupoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/UsuarioXTurmaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/UsuarioXPontoDeVistaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/UsuarioXTemaPanteonDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/TemaPanteonXMidiatecaDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/TemaPanteonXTagDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/ForumDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/UsuarioXMensagemForumDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/PropostaDeAcaoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/PropostaDeAcaoGeralDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/UsuarioXNivelAcessoDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/GeralDB.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/db/MidiatecaXPontoDeVistaDB.class.php");

// UI Includes
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/MuralDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/UsuarioXNivelAcessoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/UsuarioXTurmaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/UsuarioXGrupoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/UsuarioDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/PerfilDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/TurmaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/TipoMidiaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/TagDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/PermissaoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/NivelAcessoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/InstituicaoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/EstruturaSocialDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/TemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/SujeitoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/SituacaoProblemaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/NotaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/MidiatecaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/MensagemUsuarioDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/MetodoAnaliseDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/MensagemForumDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/ItemAnaliseDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/GrupoSocialDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/DiagnosticoIndividualDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/DiagnosticoGeralDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/GrupoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/PropostaDeAcaoGrupoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/DiagnosticoGrupoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/UsuarioXPontoDeVistaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/UsuarioXTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/TemaPanteonXMidiatecaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/TemaPanteonXTagDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/ForumDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/UsuarioXMensagemForumDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/PropostaDeAcaoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/PropostaDeAcaoGeralDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/base/PontodeVistaDBXML.class.php");

// UI Module Complexos - Criar Tema Panteon
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/MeusMetodosDeAnaliseDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/MinhasEstruturasSociaisDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarItemDeAnaliseDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarGrupoSocialDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarMidiatecaTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarPontoDeVistaTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarMuralDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarSituacaoProblemaTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarPontoDeVistaSujeitoTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/MinhaMidiatecaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarGrupoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/temapanteon/CriarUsuarioXGrupoDBXML.class.php");

// UI Module Complexos de Analise
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MinhaPropostaDeAcaoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MinhasPropostasDeAcaoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MinhaPropostaDeAcaoGeralDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MinhasMensagensDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeuMuralDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeuForumDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeuForumMensagemDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeuForumMensagensTopicoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeusPontosDeVistasDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeuTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/PesquisadoresDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeuDiagnosticoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeusDiagnosticosDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MinhasPropostasDeAcaoGrupoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeusDiagnosticosGrupoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeuDiagnosticoGeralDBXM.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MidiatecaTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/BibliotecaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/PontoDeVistaTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/MeuPontoDeVistaTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/analise/TodosPontoDeVistaTemaPanteonDBXML.class.php");

// UI Module Complexos MeuPerfil
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/meuperfil/MeusGruposDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/meuperfil/MinhasTurmasDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/meuperfil/MinhasNotasDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/meuperfil/MeuPerfilDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/meuperfil/MeusTemasPanteonDBXML.class.php");

// UI Util
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerVideoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerPontoDeVistaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerTemaPanteonDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerMetodoAnaliseDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerEstruturaSocialDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerEstruturaSocialComSujeitosDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerSujeitoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerSituacaoProblemaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerGrupoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerMensagemDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerTurmaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerPerfilDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerAjaxDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerUsuarioDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerUsuarioXPontoDeVistaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerUsuarioXTurmaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerTagDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/VerColetarDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/util/ApagarDBXML.class.php");

// Modulos de Configuracao
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ConfiguracaoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ConfigTurmaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ConfigInstituicaoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ConfigUsuarioDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ConfigUsuarioXNivelAcessoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ConfigUsuarioXTurmaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ConfigTagDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ConfigTipoMidiaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/LoginDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/configuracao/ErroDBXML.class.php");

// Modulos UI Primeira Pagina
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/intro/OQueEDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/intro/BibliotecaPublicaDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/intro/ContatoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/intro/TermoDeUsoDBXML.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/ui/intro/SobreDBXML.class.php");


// Modulos UI RanderNet
ModuleFactory::IncludePhp("randernet", "classes/ui/randernetxmlduallistasmselect.class.php");
ModuleFactory::IncludePhp("randernet", "classes/ui/randernetxmleasylist.class.php");
ModuleFactory::IncludePhp("randernet", "classes/ui/randernetxmlnukegenericcollection.class.php");
ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarProcessPageState.class.php");
ModuleFactory::IncludePhp("randernet", "classes/ui/xmleasylistajax.class.php");


class PanteonEscolarBaseModule extends BaseModule
{


  /**
   * Form Login
   * @param XmlBlockCollection $current
   */
  public function FormLogin(&$current)
  {

    $myWords = $this->WordCollection();

    $module = $this->_context->ContextValue('xmlnuke.LOGINMODULE');

    //$paragraph = new XmlParagraphCollection();

    $url = new XmlnukeManageUrl(URLTYPE::MODULE, $module);
    $url->addParam('action', 'action.LOGIN');
    $url->addParam('ReturnUrl', $this->_urlReturn);

    $form = new XmlFormCollection($this->_context, $url->getUrl(), $myWords->Value("LOGINTITLE"));
    $form->setJSValidate(true);
    //$paragraph->addXmlnukeObject($form);

    $textbox = new XmlInputTextBox('Login', 'loguser', $this->_context->ContextValue("loguser"), 12);
    $textbox->setInputTextBoxType(InputTextBoxType::TEXT);
    $textbox->setRequired(true);
    $form->addXmlnukeObject($textbox);

    $textbox = new XmlInputTextBox('Senha', 'password', '', 12);
    $textbox->setInputTextBoxType(InputTextBoxType::PASSWORD);
    $textbox->setRequired(true);
    $form->addXmlnukeObject($textbox);

    $button = new XmlInputButtons();
    $button->addSubmit("Acessar", 'submit_button_login');
    $form->addXmlnukeObject($button);

    $url = new XmlnukeManageUrl(URLTYPE::MODULE, $module);
    $url->addParam('action', ModuleActionLogin::FORGOTPASSWORD);
    $url->addParam('ReturnUrl', $this->_urlReturn);

    $label = new XmlInputLabelObjects('<div id="ajuda">Problemas?</div>');
    $link = new XmlAnchorCollection($url->getUrl(), null);
    $link->addXmlnukeObject(new XmlnukeText('<div id="link_ajuda">Esqueci a minha senha</div>'));
    $label->addXmlnukeObject($link);

    $url = new XmlnukeManageUrl(URLTYPE::MODULE, $module);
    $url->addParam('action', ModuleActionLogin::NEWUSER);
    $url->addParam('ReturnUrl', $this->_urlReturn);

    $link = new XmlAnchorCollection($url->getUrl(), null);
    $link->addXmlnukeObject(new XmlnukeText('<div id="link_ajuda">Criar Usuário</div>'));
    $label->addXmlnukeObject($link);
    $form->addXmlnukeObject($label);


    $blockMensagem = new RanderNetXmlnukeGenericCollection("blocklogin");
    $blockMensagem->addXmlnukeObject($form);

    $current->addXmlnukeObject($blockMensagem);



  }

  public function aviso($context)
  {

    if($context->getCookie("mensagem_aviso") != "")
    {
      $aviso = PanteonEscolarBaseModule::caixaAviso($context);
      $aviso->addXmlnukeObject(new XmlNukeText($context->getCookie("mensagem_aviso")));
      $context->removeCookie("mensagem_aviso");
    }

    else
    {
      $aviso = new XmlNukeText("");
    }

    return $aviso;
  }

  public function getNivelAcesso($context, $id_usuario)
  {
    $db = new UsuarioXNivelAcessoDB($context);

    return $db->obterNivelAcessoPorIDUsuario($id_usuario);
  }

  public function caixaAviso($context)
  {
    $container = new XmlnukeUIAlert($context, UIAlert::BoxAlert);
    $container->setAutoHide(5000);
    $container->setUIAlertType(UIAlert::BoxInfo);

    return $container;
  }

  public function meusPontosDeVistas($id_usuario, $id_tema_panteon, $context)
  {
    $span = new XmlnukeSpanCollection();
    $db = new UsuarioXPontoDeVistaDB($context);
    $itC = $db->obterTodosOsPontoDeVistaPorIDUsuarioXIDTemaPanteonColetados($id_usuario, $id_tema_panteon, '1');
    $itD = $db->obterTodosOsPontoDeVistaPorIDUsuarioXIDTemaPanteonColetados($id_usuario, $id_tema_panteon, '0');
    $context = new Context();
    $url = $context->bindModuleUrl("panteonescolar.meuspontosdevistas");
    $texto .= "<div id='meusPontosDeVistas'> Ponto(s) de Vista(s): ";
    $texto .= " <a href='".$url."&coletados=1'>(" . $itC->Count() . ") Coletado(s)</a>. ";
    $texto .= " <a href='".$url."&coletados=0'>(" . $itD->Count() . ") Descartados(s)</a>";
    $texto .= "</div>";

    return new XmlnukeText($texto);
  }

  public function definirTitulo($original, $temaPanteonDefinido, $voltar = "")
  {
    $url_voltar = '<a style="color: #A82B1C; font-size: 10px;" href="javascript:history.go(-1)">Anterior</a> > ';

    if($temaPanteonDefinido == "")
    {
      $titulo = new XmlnukeDocument($original . " (Escolha um Tema Panteon)", $url_voltar . $original . " (Escolha um Tema Panteon)");
    }

    else
    {
      $tema_panteon = $temaPanteonDefinido;

      if(strlen($temaPanteonDefinido) > 21)
      {
        $tema_panteon = substr($temaPanteonDefinido, 0, 21) . '...';
      }

      $titulo_tema_panteon = $tema_panteon;
      $url = $this->_context->bindModuleUrl("panteonescolar.meutemapanteon");
      $url_tema_panteon = '<a style="color: #A82B1C;" href="'.$url.'">' . $tema_panteon . '</a>';


      // title e abstract
      $titulo = new XmlnukeDocument($original . " (" . $titulo_tema_panteon . ")", $url_voltar . $original . " (" . $url_tema_panteon . ")");
    }

    return $titulo;
  }

  public function definirTituloSimples($titulo_original)
  {
    $url_voltar = '<a style="color: #A82B1C; font-size: 10px;" href="javascript:history.go(-1)">Anterior</a> > ';
    $titulo = new XmlnukeDocument($titulo_original, $url_voltar . $titulo_original);

    return $titulo;
  }

  // curPageURL
  public function curPageURL()
  {
    $pageURL = 'http';

    if($_SERVER["HTTPS"] == "on")
    {
      $pageURL .= "s";
    }

    $pageURL .= "://";

    if($_SERVER["SERVER_PORT"] != "80")
    {
      $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }

    else
    {
      $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }

    return $pageURL;
  }

  // criarTitulo
  public function criarTitulo($node, $titulo = "Panteon Escolar")
  {
    $body = XmlUtil::CreateChild($node, "box_titulo", "");
    XmlUtil::AddAttribute($body, "titulo", $titulo);
  }

  // preencherBarraMeusGrupos
  public function preencherBarraMeusGrupos($node, $itDB, $titulo)
  {

    //xmlnuke.php?module=panteonescolar.verperfil&site=PanteonEscolar&xsl=page&lang=pt-br&site=PanteonEscolar&xsl=ver&lang=pt-br&verperfil=
    $titulo = strip_tags($titulo);


    while($itDB->hasNext())
    {
      $sr = $itDB->moveNext();

      $body = XmlUtil::CreateChild($node, "mensagem", "");

      if($titulo != "")
      {
        $context = new Context();
        $url = $context->bindModuleUrl("panteonescolar.vergrupo", "ver") . "&amp;&amp;site=PanteonEscolar&amp;xsl=page&amp;lang=pt-br&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vergrupo=" . $sr->getField("id_grupo") ;
        XmlUtil::AddAttribute($body, "titulo","<b><a class='lista_direita_detalhe' href='".$url."'>" . $sr->getField($titulo) . "</a></b><br/>");
      }

      XmlUtil::AddAttribute($body, "conteudo", $texto);
      XmlUtil::AddAttribute($body, "autor", $sr->getField($autor));

      if(($link != "") && ($id != ""))
      {
        XmlUtil::AddAttribute($body, "link", $linkFinal);
      }
    }
  }

  // preencherBarraMinhasTurmas
  public function preencherBarraMinhasTurmas($node, $itDB, $titulo)
  {

    //xmlnuke.php?module=panteonescolar.verperfil&site=PanteonEscolar&xsl=page&lang=pt-br&site=PanteonEscolar&xsl=ver&lang=pt-br&verperfil=
    $titulo = strip_tags($titulo);


    while($itDB->hasNext())
    {
      $sr = $itDB->moveNext();

      $body = XmlUtil::CreateChild($node, "mensagem", "");

      if($titulo != "")
      {
        $context = new Context();
        $url = $context->bindModuleUrl("panteonescolar.verturma", "ver") . "&amp;&amp;site=PanteonEscolar&amp;xsl=page&amp;lang=pt-br&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;verturma=" . $sr->getField("id_turma") ;
        XmlUtil::AddAttribute($body, "titulo","<b><a class='lista_direita_detalhe' href='".$url."'>" . $sr->getField($titulo) . "</a></b><br/>");
      }

      XmlUtil::AddAttribute($body, "conteudo", $texto);
      XmlUtil::AddAttribute($body, "autor", $sr->getField($autor));

      if(($link != "") && ($id != ""))
      {
        XmlUtil::AddAttribute($body, "link", $linkFinal);
      }
    }
  }

  // preencherBarra
  public function preencherBarra($node, $itDB, $titulo, $conteudo = "", $autor = " ", $link = " ", $id = "", $texto_label="Leia Mais", $texto_title="Detalhes ")
  {
    $tam_max = 330;
    //xmlnuke.php?module=panteonescolar.verperfil&site=PanteonEscolar&xsl=page&lang=pt-br&site=PanteonEscolar&xsl=ver&lang=pt-br&verperfil=
    $titulo = strip_tags($titulo);
    $conteudo = strip_tags($conteudo);
    $autor = strip_tags($autor);

    while($itDB->hasNext())
    {
      $sr = $itDB->moveNext();
      $texto = $sr->getField($conteudo);

      if(($link != "") && ($id != ""))

      {
        $linkFinal = '<div id="link_barra_direita">' . '<a class="lista_direita_detalhe" ' . 'title=" '. $texto_title . $sr->getField($autor) . '" href="' . $link . $sr->getField($id) . '"> '.$texto_label.' </a></div>';
      }

      if(strlen($texto) > $tam_max)
      {
        $texto = strip_tags(substr($texto, 0, $tam_max));
        $texto = $texto;
      }

      $body = XmlUtil::CreateChild($node, "mensagem", "");

      if($titulo != "")
      {
        $context = new Context();
        $url = $context->bindModuleUrl("panteonescolar.verperfil", "ver") . "&amp;&amp;site=PanteonEscolar&amp;xsl=page&amp;lang=pt-br&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;verperfil=" . $sr->getField($id) ;
        XmlUtil::AddAttribute($body, "titulo","<b><a class='lista_direita_detalhe' href='".$url."'>" . $sr->getField($titulo) . "</a></b><br/>");
      }

      XmlUtil::AddAttribute($body, "conteudo", $texto);
      XmlUtil::AddAttribute($body, "autor", $sr->getField($autor));

      if(($link != "") && ($id != ""))
      {
        XmlUtil::AddAttribute($body, "link", $linkFinal);
      }
    }
  }

  // preencherBarraVazia
  public function preencherBarraVazia($node)
  {
    $body = XmlUtil::CreateChild($node, "mensagem", "");
    XmlUtil::AddAttribute($body, "titulo", "Não há Informações");
    XmlUtil::AddAttribute($body, "conteudo", " ");
    XmlUtil::AddAttribute($body, "autor", " ");
  }

  // preencherBarraMensagensNaoLidas
  public function preencherBarraMensagensNaoLidas($node, $itDB, $titulo, $textoMensagem)
  {
    $tam_max_mensagem = 33;

    $titulo = strip_tags($titulo);

    while($itDB->hasNext())
    {
      $sr = $itDB->moveNext();
      $mensagem = $sr->getField($textoMensagem);

      if(strlen($mensagem) > $tam_max_mensagem)
      {
        $mensagem = strip_tags(substr($mensagem, 0, $tam_max_mensagem));
        $mensagem = $mensagem." ...";
      }

      $body = XmlUtil::CreateChild($node, "mensagem", "");

      if($titulo != "")
      {
        $context = new Context();
        //$url = $context->bindModuleUrl("panteonescolar.vermensagem", "ver") . "&amp;&amp;site=PanteonEscolar&amp;xsl=page&amp;xml=home&amp;lang=pt-br&amp;vermensagem=" . $sr->getField("id_mensagem_usuario");
        $url = $context->bindModuleUrl("panteonescolar.vermensagem", "ver") . "&amp;&amp;site=PanteonEscolar&amp;xsl=page&amp;lang=pt-br&amp;site=PanteonEscolar&amp;xsl=ver&amp;lang=pt-br&amp;vermensagem=".$sr->getField("id_mensagem_usuario");
        XmlUtil::AddAttribute($body, "titulo","<b><a class='lista_direita_detalhe' href='".$url."'>" . $sr->getField($titulo) . "</a></b><br/>");
      }

      XmlUtil::AddAttribute($body, "conteudo", $mensagem);
      //XmlUtil::AddAttribute($body, "autor", $sr->getField($autor));


    }
  }

  public function preencherBarraComTexto($node, $titulo, $conteudo, $autor)
  {
    $titulo = strip_tags($titulo);
    $conteudo = strip_tags($conteudo);
    $autor = strip_tags($autor);

    $body = XmlUtil::CreateChild($node, "mensagem", "");
    XmlUtil::AddAttribute($body, "titulo", $titulo);
    XmlUtil::AddAttribute($body, "conteudo", $conteudo);
    XmlUtil::AddAttribute($body, "autor", $autor);
  }

  // preencherMenuUsuario
  public function preencherMenuUsuario($ativo = "")
  {
    $menu = array(PanteonEscolarMenu::PontoDeVista => "module:panteonescolar.todospontodevistatemapanteon",
                  PanteonEscolarMenu::Diagnostico => "module:panteonescolar.meusdiagnosticos",
                  PanteonEscolarMenu::PlanoDeAcao => "module:panteonescolar.minhaspropostasdeacao",
                  PanteonEscolarMenu::Forum => "module:panteonescolar.meuforummensagem",
                  PanteonEscolarMenu::Midiateca => "module:panteonescolar.midiatecatemapanteon",
                 );

    if($ativo != "")
    {
      $opcao = $menu[$ativo];

      $arquivo = substr($ativo, 0, strrpos($ativo, '.')) . "Ativo.png";

      $keys = array_keys($menu);
      $values = array_values($menu);

      foreach($keys as $k => $v)
      {
        if($v == $ativo)
        {
          $keys[$k] = $arquivo;
        }
      }

      $menu = array_combine($keys, $values);
    }

    return $menu;
  }

  // preencherMenuBiblioteca
  public function preencherMenuBiblioteca($ativo = "")
  {

    $menu = array(PanteonEscolarMenu::Biblioteca => "biblioteca");

    if($ativo != "")
    {
      $opcao = $menu[$ativo];

      $arquivo = substr($ativo, 0, strrpos($ativo, '.')) . "Ativo.png";

      $keys = array_keys($menu);
      $values = array_values($menu);

      foreach($keys as $k => $v)
      {
        if($v == $ativo)
        {
          $keys[$k] = $arquivo;
        }
      }

      $menu = array_combine($keys, $values);
    }

    return $menu;
  }

  // preencherMenuTemaPanteon
  public function preencherMenuTemaPanteon($ativo = "")
  {

    $menu = array(PanteonEscolarMenu::TemaPanteon => "module:panteonescolar.meutemapanteon");

    if($ativo != "")
    {
      $opcao = $menu[$ativo];

      $arquivo = substr($ativo, 0, strrpos($ativo, '.')) . "Ativo.png";

      $keys = array_keys($menu);
      $values = array_values($menu);

      foreach($keys as $k => $v)
      {
        if($v == $ativo)
        {
          $keys[$k] = $arquivo;
        }
      }

      $menu = array_combine($keys, $values);
    }

    return $menu;
  }

  // preencherMenuCriarTemaPanteon
  public function preencherMenuCriarTemaPanteon($ativo = "")
  {
    $menu = array(PanteonEscolarMenu::MetodoDeAnalise => "module:panteonescolar.meusmetodosdeanalise",
                  PanteonEscolarMenu::EstruturaSocial => "module:panteonescolar.minhasestruturassociais",
                  PanteonEscolarMenu::CriarTema => "module:panteonescolar.criartemapanteon",
                 );

    if($ativo != "")
    {
      $opcao = $menu[$ativo];

      $arquivo = substr($ativo, 0, strrpos($ativo, '.')) . "Ativo.png";

      $keys = array_keys($menu);
      $values = array_values($menu);

      foreach($keys as $k => $v)
      {
        if($v == $ativo)
        {
          $keys[$k] = $arquivo;
        }
      }

      $menu = array_combine($keys, $values);
    }

    return $menu;
  }

  public function preencherMenuPadrao($node, $ativo = "")
  {
    $menu = array(PanteonEscolarMenu::MeuPerfil => "module:panteonescolar.meuperfil",
                  PanteonEscolarMenu::MeusTemas => "module:panteonescolar.meustemaspanteon",
                  PanteonEscolarMenu::Grupos => "module:panteonescolar.meusgrupos",
                  PanteonEscolarMenu::Turmas => "module:panteonescolar.minhasturmas",
                  PanteonEscolarMenu::Anotacoes => "module:panteonescolar.minhasnotas"
                 );

    if($ativo != "")
    {
      $opcao = $menu[$ativo];

      $arquivo = substr($ativo, 0, strrpos($ativo, '.')) . "Ativo.png";

      $keys = array_keys($menu);
      $values = array_values($menu);

      foreach($keys as $k => $v)
      {
        if($v == $ativo)
        {
          $keys[$k] = $arquivo;
        }
      }

      $menu = array_combine($keys, $values);
    }

    $body = PanteonEscolarBaseModule::preencherMenu($node, $menu);

    return $body;
  }

  public function preencherMenuConfig($ativo = "", $permissao = "")
  {
    $menu = array(
              PanteonEscolarMenu::Instituicao => "module:panteonescolar.configinstituicao",
              PanteonEscolarMenu::Usuario => "module:panteonescolar.configusuario",
              PanteonEscolarMenu::Turmas => "module:panteonescolar.configturma",
              PanteonEscolarMenu::Tag => "module:panteonescolar.configtag",
            );

    if($permissao == 'ADMINISTRADOR')
    {
      $menu[PanteonEscolarMenu::TipoMidia] = "module:panteonescolar.configtipomidia";
    }

    if($ativo != "")
    {
      $opcao = $menu[$ativo];

      $arquivo = substr($ativo, 0, strrpos($ativo, '.')) . "Ativo.png";

      $keys = array_keys($menu);
      $values = array_values($menu);

      foreach($keys as $k => $v)
      {
        if($v == $ativo)
        {
          $keys[$k] = $arquivo;
        }
      }

      $menu = array_combine($keys, $values);
    }

    return $menu;
  }

  // preencherMenu
  public function preencherMenu($node, $menu)
  {
    foreach($menu as $imagem => $endereco)
    {
      $body = XmlUtil::CreateChild($node, "imagem_aba", "");
      XmlUtil::AddAttribute($body, "src", $imagem);
      XmlUtil::AddAttribute($body, "href", $endereco);
    }

    return $body;
  }

  //
  // Menu de Cabeçalho
  //

  public function preencherMenuHead($node, $menu)
  {
    foreach($menu as $href => $titulo)
    {
      $body = XmlUtil::CreateChild($node, "opcao_menu", "");
      XmlUtil::AddAttribute($body, "href", $href);
      XmlUtil::AddAttribute($body, "titulo", $titulo);
    }

    return $body;
  }

  public function preencherMenuHeadAuxiliar($node, $menu)
  {
    foreach($menu as $href => $titulo)
    {
      $body = XmlUtil::CreateChild($node, "opcao_menu_auxiliar", "");
      XmlUtil::AddAttribute($body, "href", $href);
      XmlUtil::AddAttribute($body, "titulo", $titulo);
    }

    return $body;
  }

  public function preencherMenuHeadPadrao($nivel_acesso = "", $ativo = "")
  {
    $menu = array("module:panteonescolar.meuperfil" => "Meu Perfil",
                  "module:panteonescolar.meutemapanteon" => "Tema Panteon",
                  "module:panteonescolar.biblioteca" => "Biblioteca de Temas Panteon",
                  "module:panteonescolar.oquee" => "Sobre o Panteon Escolar",
                  "module:panteonescolar.configinstituicao" => "Configuração",
                 );

    if(($nivel_acesso != "GESTOR") && ($nivel_acesso != "ADMINISTRADOR") && ($nivel_acesso != "EDITOR"))
    {
      unset($menu["module:panteonescolar.configinstituicao"]);
    }

    if($menu[$ativo])
    {
      $menu[$ativo] = '<div style="color:#A42112">' . $menu[$ativo] . '</div>';
    }

    return $menu;
  }

  public function preencherMenuHeadInicial($ativo = "")
  {
    $menu = array("module:panteonescolar.oquee" => "o que é?",
                  "module:panteonescolar.bibliotecapublica" => "biblioteca de temas",
                  "module:panteonescolar.contato" => "contato");

    if($menu[$ativo])
    {
      $menu[$ativo] = '<div style="color:#A42112">' . $menu[$ativo] . '</div>';
    }

    return $menu;
  }
  /**
   *
   * @param Context $context
   * @param string $ativo
   * @return string
   */
  public function preencherMenuHeadInicialAcesso($context, $ativo = "")
  {
    $menu = array('module:?module=panteonescolar.panteonescolarlogin&amp;action=action.NEWUSER&amp;ReturnUrl=%2fmeuperfil&site=PanteonEscolar&amp;xsl=page&amp;xml=home&amp;lang=pt-br' => "cadastrar-se");

    if($menu[$ativo])
    {
      $menu[$ativo] = '<div style="color:#A42112">' . $menu[$ativo] . '</div>';
    }

    return $menu;
  }

  function idade($aniversario)
  {

    return floor((time() - strtotime($aniversario)) / 31556926);
  }

}

class RanderNetXmlInputObjectType extends XmlInputObjectType
{
  const RANDERNET_DATE = 50;
  const RANDERNET_DUALLIST_ASMSELECT = 51;
  const RANDERNET_SELECT_AJAX = 52;
}

class PanteonEscolarMyProcess extends PanteonEscolarProcessPageState
{

  /**
   * @desc
   * @param ProcessPageField $field
   * @param string $curValue
   * @return IXmlnukeDocumentObject
   */
  public function renderField($field, $curValue)
  {

    if($field->fieldXmlInput == XmlInputObjectType::CUSTOM)
    {
      return new XmlInputFile($field->fieldCaption);
    }

    else
    {
      return parent::renderField($field, $curValue);
    }


    if(($field->fieldXmlInput == XmlInputObjectType::TEXTBOX) || ($field->fieldXmlInput == XmlInputObjectType::PASSWORD) || ($field->fieldXmlInput == XmlInputObjectType::TEXTBOX_AUTOCOMPLETE))
    {
//      XmlInputTextBox $itb
      $itb = new XmlInputTextBox($field->fieldCaption, $field->fieldName, $curValue, $field->size);
      $itb->setRequired($field->required);
      $itb->setRange($field->rangeMin, $field->rangeMax);
      $itb->setDescription($field->fieldCaption);

      if($field->fieldXmlInput == XmlInputObjectType::PASSWORD)
      {
        $itb->setInputTextBoxType(InputTextBoxType::PASSWORD);
      }

      elseif($field->fieldXmlInput == XmlInputObjectType::TEXTBOX_AUTOCOMPLETE)
      {
        if(!is_array($field->arraySelectList) || ($field->arraySelectList["URL"] == "") || ($field->arraySelectList["PARAMREQ"] == ""))
        {
          throw new XMLNukeException(
            "You have to pass a array to arraySelectList field parameter with the following keys: URL, PARAMREQ. Optional: ATTRINFO, ATTRID, JSCALLBACK");
        }

        $itb->setInputTextBoxType(InputTextBoxType::TEXT);
        $itb->setAutosuggest($this->_context, $field->arraySelectList["URL"], $field->arraySelectList["PARAMREQ"], $field->arraySelectList["ATTRINFO"], $field->arraySelectList["ATTRID"], $field->arraySelectList["JSCALLBACK"]);
      }

      else
      {
        $itb->setInputTextBoxType(InputTextBoxType::TEXT);
      }

      $itb->setReadOnly($this->isReadOnly($field));
      $itb->setMaxLength($field->maxLength);
      $itb->setDataType($field->dataType);
      return $itb;
    }

    else if(($field->fieldXmlInput == XmlInputObjectType::RADIOBUTTON) || ($field->fieldXmlInput == XmlInputObjectType::CHECKBOX))
    {
//      XmlInputCheck $ic
      $ic = new XmlInputCheck($field->fieldCaption, $field->fieldName, $field->defaultValue);

      if($field->fieldXmlInput == XmlInputObjectType::TEXTBOX)
      {
        $ic->setType(InputCheckType::CHECKBOX);
      }

      else
      {
        $ic->setType(InputCheckType::CHECKBOX);
      }

      $ic->setChecked($field->defaultValue == $curValue);
      $ic->setReadOnly($this->isReadOnly($field));
      return $ic;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::MEMO)
    {
//      XmlInputMemo $im
      $im = new XmlInputMemo($field->fieldCaption, $field->fieldName, $curValue);
      $im->setWrap("SOFT");
      $im->setSize(50, 8);
      $im->setReadOnly($this->isReadOnly($field));
      return $im;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::HTMLTEXT)
    {
//      XmlInputMemo $im
      $im = new XmlInputMemo($field->fieldCaption, $field->fieldName, $curValue);
      //$im->setWrap("SOFT");
      //$im->setSize(50, 8);
      $im->setVisualEditor(true);
      $im->setReadOnly($this->isReadOnly($field));
      return $im;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::HIDDEN)
    {
//      XmlInputHidden $ih
      $ih = new XmlInputHidden($field->fieldName, $curValue);
      return $ih;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::SELECTLIST)
    {
//      XmlEasyList $el
      $el = new XmlEasyList(EasyListType::SELECTLIST, $field->fieldName, $field->fieldCaption, $field->arraySelectList, $curValue);
      $el->setReadOnly($this->isReadOnly($field));
      return $el;
    }

    else if($field->fieldXmlInput == RanderNetXmlInputObjectType::SELECTLIST)
    {
//      XmlEasyList $el
      $el = new XmlEasyList(EasyListType::SELECTLIST, $field->fieldName, $field->fieldCaption, $field->arraySelectList, $curValue);
      $el->setReadOnly($this->isReadOnly($field));
      return $el;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::DUALLIST)
    {
      $ards = new ArrayDataSet($field->arraySelectList, "value");
      $duallist = new XmlDualList($this->_context, $field->fieldName, $this->_lang->Value("TXT_AVAILABLE", $field->fieldCaption), $this->_lang->Value("TXT_USED", $field->fieldCaption));
      $duallist->createDefaultButtons();
      $duallist->setDataSourceFieldName("key", "value");

      if($curValue != "")
      {
        $ardt = explode(",", $curValue);
        $ardt = array_flip($ardt);
        foreach($ardt as $key => $value)
        {
          $ardt[$key] = $field->arraySelectList[$key];
        }
      }

      else
      {
        $ardt = array();
      }

      $ards2 = new ArrayDataSet($ardt, "value");

      $duallist->setDataSource($ards->getIterator(), $ards2->getIterator());

      $label = new XmlInputLabelObjects("=>");
      $label->addXmlnukeObject($duallist);

      return $label;
    }

    else if($field->fieldXmlInput == RanderNetXmlInputObjectType::RANDERNET_DUALLIST_ASMSELECT)
    {
      $ards = new ArrayDataSet($field->arraySelectList, "value");
      //$duallist = new XmlDualList($this->_context, $field->fieldName, $this->_lang->Value("TXT_AVAILABLE", $field->fieldCaption), $this->_lang->Value("TXT_USED", $field->fieldCaption));
      $duallist = new RanderNetXmlDualListASMSelect($this->_context, $field->fieldName, $this->_lang->Value("TXT_AVAILABLE", $field->fieldCaption));
      //$duallist->createDefaultButtons();
      $duallist->setDataSourceFieldName("key", "value");

      if($curValue != "")
      {
        $ardt = explode(",", $curValue);
        $ardt = array_flip($ardt);
        foreach($ardt as $key => $value)
        {
          $ardt[$key] = $field->arraySelectList[$key];
        }
      }

      else
      {
        $ardt = array();
      }

      $ards2 = new ArrayDataSet($ardt, "value");

      $duallist->setDataSource($ards->getIterator(), $ards2->getIterator());

      $label = new XmlInputLabelObjects("=>");
      $label->addXmlnukeObject($duallist);

      return $label;
    }

    else if(($field->fieldXmlInput == XmlInputObjectType::DATE) || ($field->fieldXmlInput == XmlInputObjectType::DATETIME))
    {
      $cur = explode(" ", $curValue);
      $idt = new XmlInputDateTime($field->fieldCaption, $field->fieldName, $this->_dateFormat, ($field->fieldXmlInput == XmlInputObjectType::DATETIME), $cur[0], $cur[1]);
      return $idt;
    }

    else if(($field->fieldXmlInput == RanderNetXmlInputObjectType::RANDERNET_DATE) || ($field->fieldXmlInput == SgemXmlInputObjectType::RANDERNET_DATETIME))
    {
      $cur = explode(" ", $curValue);
      $idt = new RanderNetXmlInputDateTime($field->fieldCaption, $field->fieldName, $this->_dateFormat, ($field->fieldXmlInput == XmlInputObjectType::DATETIME), $cur[0], $cur[1]);
      return $idt;
    }

    else if($field->fieldXmlInput == XmlInputObjectType::FILEUPLOAD)
    {
      $file = new XmlInputFile($field->fieldCaption, $field->fieldName);
      return $file;
    }

    else
    {
//      XmlInputLabelField xlf
      $xlf = new XmlInputLabelField($field->fieldCaption, $curValue);
      return $xlf;
    }
  }

}

ModuleFactory::IncludePhp("panteonescolar", "classes/base/PanteonEscolarBaseLogin.class.php");
ModuleFactory::IncludePhp("panteonescolar", "modules/panteonescolarbasiclogin.class.php");
ModuleFactory::IncludePhp("panteonescolar", "modules/panteonescolarlogin.class.php");
?>
