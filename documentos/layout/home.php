<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >

<head>

  <title>PANTEON ESCOLAR</title>
  <link rel="shortcut icon" href="favicon.ico" />
<link href="estilos/principal.css" rel="stylesheet" type="text/css" media="screen" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<script type="text/javascript">
function ajax(div,url)
	{
		var o=window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Msxml2.XMLHTTP.3.0");
		o.onreadystatechange=function()
			{ 
				if(o.readyState==4 && o.status==200) document.getElementById(div).innerHTML=o.responseText;
			}
		o.open('GET',url,true);
		o.send(null);
	}
</script>

</head>

<body>
<div id="baseLayout">
  <div id="Layout">
  
    <div id="Topo">
   	  <div id="Head">
   	    <div id="HeadMenu">
        	<ul>
            	<li><a href="index.php">home</a></li>
                <li><a href="javascript:;">Suporte</a></li>
                <li><a href="javascript:;">contato</a></li>
            </ul>
        </div>
   	  </div>
   	  <div id="AreaTopo">
   	    <div id="areaMarca"><a href="index.php"><img src="images/Layout/Marca.png" width="182" height="167" alt="PANTEON ESCOLAR" longdesc="images/Layout/Marca.png" title="PANTEON ESCOLAR" /></a></div>
   	  </div>
    </div>
  </div>
  <div id="BarraMenu">
  	<ul>
    	<li title="Home"><a href="#Ponto01" onclick="javascript:ajax('boxBaixoEsquerda', 'meusTemas.php');">meus temas panteon</a></li>
        <li title="Home"><a href="#Ponto01" onclick="javascript:ajax('boxBaixoEsquerda', 'biblioteca.php');" title="Home">biblioteca</a></li>
        <li title="Home"><a href="#Ponto01" onclick="javascript:ajax('boxBaixoEsquerda', 'sobrePanteon.php');" title="Home">sobre panteon</a></li>
        <li title="Home"><a href="#Ponto01" onclick="javascript:ajax('boxBaixoEsquerda', 'Mensagens.php');" title="Home">mensagens</a></li>
        <li title="Home"><a href="#Ponto01" onclick="javascript:ajax('boxBaixoEsquerda', 'chat.php');" title="Home">chat</a></li>
        <li title="Home"><a href="#Ponto01" onclick="javascript:ajax('boxBaixoEsquerda', 'configuracoes.php');" title="Home">configurações</a></li>
    </ul>
  </div>
  <div id="content">
    <div id="BoxUsuario">
      <div id="MenuBoxUsuario"><a href="javascript:void(0)" onclick="javascript:ajax('conteudoBoxUsuario', 'meuperfil.php');"><img src="images/Layout/MenuUsuario/meuPerfil.png" width="143" height="39" alt="Meu Perfil" longdesc="images/Layout/MenuUsuario/meuPerfil.png" title="Meu Perfil" /></a><a href="javascript:void(0)" onclick="javascript:ajax('conteudoBoxUsuario', 'turmas.php');"><img src="images/Layout/MenuUsuario/Turmas.png" width="143" height="39" alt="Turmas" longdesc="images/Layout/MenuUsuario/Turmas.png" title="Turmas"  /></a><a href="javascript:void(0)" onclick="javascript:ajax('conteudoBoxUsuario', 'grupos.php');"><img src="images/Layout/MenuUsuario/Grupos.png" width="143" height="39" alt="Grupos" longdesc="images/Layout/MenuUsuario/Grupos.png" title="Grupos" /></a><a href="javascript:void(0)" onclick="javascript:ajax('conteudoBoxUsuario', 'mural.php')"><img src="images/Layout/MenuUsuario/Mural.png" width="143" height="39" alt="Mural" longdesc="images/Layout/MenuUsuario/Mural.png" title="Mural" /></a><a href="javascript:void(0)" onclick="javascript:ajax('conteudoBoxUsuario', 'anotacoes.php')"><img src="images/Layout/MenuUsuario/Anotacoes.png" width="143" height="39" alt="Anota&ccedil;&otilde;es" longdesc="images/Layout/MenuUsuario/Anotacoes.png" title="Anotações" class="img02" /></a></div>
      <div id="conteudoBoxUsuario">
        <div id="ContUsuario">
          <div id="BoxUsuarioUsuario">
            <div id="BoxUsuarioUsuarioTitulo">&gt;Pesquisador</div>
          <a href="index.html"><img src="images/Layout/Locadores/FotoUsuario.png" width="142" height="189" alt="Nome do Usu&aacute;rio" longdesc="images/Layout/Locadores/FotoUsuario.png" title="Nome do usuário" /></a></div>
          <div id="BoxUsuarioInformacoes">
            <div id="BoxUsuarioTitulos">
              <div id="BoxUsuarioNomeUsuario">ANTONIO GALDINO</div>
              <div id="BoxUsuarioBotoes1">
              	<ul>
                	<li><a href="javascript:void(0)" onclick="javascript:ajax('conteudoBoxUsuario', 'editarPerfil.php')" title="Editar Perfil">editar perfil</a></li>
                  <li><a href="index.php" title="Sair do sistema">sair</a></li>
                </ul>
              </div>
            </div>
            <div id="BoxUsuariosInf">
              <form id="form1" name="form1" method="post" action="">
                <label>
                <input name="textfield" type="text" class="BoxUsuariosCaixasForm" id="textfield" />
                </label>
                <label></label>
                <input name="imageField" type="image" class="botaoForm01" id="imageField" src="images/Layout/BotaoForm01.png" />
              </form>
            </div>
            <div id="BoxUsuariosInf2">Postado em Arquitetura da  Informa&ccedil;&atilde;o, Design, Design de Intera&ccedil;&atilde;o, <em>Perfil</em> de <em>Usu&aacute;rio</em>, Sem Categoria, Simplicidade, Testes e Pesquisas</div>
            <div id="BoxUsuariosInf3">
              <p>INSTITUI&Ccedil;&Atilde;O: COLEGIO TEIXEIRA LEAL</p>
              <p>LOCAL: SALVADOR - BAHIA</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <a name="Ponto01" id="Ponto01"></a>
    <div id="boxBaixoEsquerda">
      <div id="boxBaixoEsquerdaTitulo">
        <div id="BoxTituloBoxBaixo">meu panteon</div>
      </div>
      <div id="boxBaixoEsquerdaContent">
        <div id="conteudoBoxBaixoHome">
          <p><strong>Seja bem vindo,</strong></p>
          <p>&nbsp;</p>
          <p>O Panteon &eacute; uma interface de cria&ccedil;&atilde;o, an&aacute;lise e diagn&oacute;stico de estudos de casos organizacionais e sociais. Com ele podemos fazer estudos de casos on-line atrav&eacute;s da aplica&ccedil;&atilde;o de modelos de an&aacute;lise gen&eacute;ricos a situa&ccedil;&otilde;es-problema espec&iacute;ficas. As informa&ccedil;&otilde;es contidas nos casos s&atilde;o provenientes de um estudo de campo, onde &eacute; realizada a coleta das fontes textuais e multim&iacute;dias, e s&atilde;o realizadas entrevistas com os personagens envolvidos.</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>Um caso no Panteon &eacute; formado basicamente pelos seguintes elementos: dados da organiza&ccedil;&atilde;o e da sua estrutura; modelo de an&aacute;lise; situa&ccedil;&otilde;es-problema; personagens; pontos de vista dos personagens e arquivos anexos.Cada personagem oferece um Ponto de Vista &iacute;mpar de acordo com a sua posi&ccedil;&atilde;o hier&aacute;rquica e a sua especializa&ccedil;&atilde;o funcional. Deste modo, os problemas organizacionais s&atilde;o representados de acordo com os Pontos de Vista de cada personagem, sobre cada situa&ccedil;&atilde;o-problema, com base nas diferentes Categorias de An&aacute;lise do modelo escolhido.</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>Para fazer an&aacute;lise de um Caso pela primeira vez, basta selecion&aacute;-lo em &quot;Biblioteca&quot;, caso voc&ecirc; deseje continuar uma an&aacute;lise j&aacute; iniciada, clique no bot&atilde;o &quot;Meus casos&quot;. Ap&oacute;s selecionar o Caso, para elaborar seus diagn&oacute;sticos, siga as op&ccedil;&otilde;es do menu da esquerda. A cria&ccedil;&atilde;o de casos s&oacute; &eacute; permitida para usu&aacute;rios com perfil espec&iacute;fico. Caso deseje uma instru&ccedil;&atilde;o mais detalhada consulte a op&ccedil;&atilde;o de &quot;Ajuda&quot; e baixe o manual do sistema.          </p>
          <p>Bom trabalho!</p>
        </div>
      </div>
      <div id="boxBaixoEsquerdaRodape"></div>
    </div>
    <div id="boxDireita">
      <p><a href="index.html"><img src="images/Layout/botaoBuscar.png" width="203" height="49" alt="Buscar" longdesc="images/Layout/botaoBuscar.png" title="Inicie a busca" /></a></p>
      <p><a href="./index.html"><img src="images/Layout/btnCriarTema.png" width="210" height="45" alt="Criar Tema" longdesc="images/Layout/btnCriarTema.png" title="Criar tema" /></a></p>
      <p><img src="images/Layout/TituloForumHome.png" width="226" height="71" alt="Forum Panteon" longdesc="images/Layout/TituloForumHome.png" /></p>
      <div id="baseForumHome">
        <div id="ForumHomeContent">
          <p>Postagem  postagem, <br />
            postagem postagem posta<br />
            gem e postagempos<br />
          tagem?...</p>
          <p>NOME GRUPO ou TURMA-USU&Aacute;RIO</p>
          <p>&nbsp;</p>
          <p class="pcentro"><img src="images/Separador.png" alt="Separador" width="14" height="13" class="img1" longdesc="images/Separador.png" /></p>
          <p>&nbsp;</p>
          <p>Postagem  postagem, <br />
postagem postagem posta<br />
gem e postagempos<br />
tagem?...</p>
          <p>NOME GRUPO ou TURMA-USU&Aacute;RIO</p>
          <p>&nbsp;</p>
          <p class="pcentro"><img src="images/Separador.png" alt="Separador" width="14" height="13" class="img1" longdesc="images/Separador.png" /></p>
          <p>&nbsp;</p>
          
          <p>Postagem  postagem, <br />
postagem postagem posta<br />
gem e postagempos<br />
tagem?...</p>
          <p>NOME GRUPO ou TURMA-USU&Aacute;RIO</p>
          <p>&nbsp;</p>
          <p class="pcentro"><img src="images/Separador.png" alt="Separador" width="14" height="13" class="img1" longdesc="images/Separador.png" /></p>
          <p>&nbsp;</p>
          
          <p>Postagem  postagem, <br />
postagem postagem posta<br />
gem e postagempos<br />
tagem?...</p>
          <p>NOME GRUPO ou TURMA-USU&Aacute;RIO</p>
          <p>&nbsp;</p>
          <p class="pcentro"><img src="images/Separador.png" alt="Separador" width="14" height="13" class="img1" longdesc="images/Separador.png" /></p>
          <p>&nbsp;</p>
          
          <p>Postagem  postagem, <br />
postagem postagem posta<br />
gem e postagempos<br />
tagem?...</p>
          <p>NOME GRUPO ou TURMA-USU&Aacute;RIO          </p>
          <p>&nbsp;</p>
          <p class="pcentro"><img src="images/Separador.png" alt="Separador" width="14" height="13" class="img1" longdesc="images/Separador.png" /></p>
          <p>&nbsp;</p>
          <p>Postagem  postagem, <br />
            postagem postagem posta<br />
            gem e postagempos<br />
            tagem?...</p>
          <p>NOME GRUPO ou TURMA-USU&Aacute;RIO          <br />
          </p>
</div>
      </div>
      <div id="baseForumHomeRodape"></div>
    
    </div>
    <div id="rodape">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p><strong>PANTEON ESCLAR</strong><br />
      Sistema Educacional aplicado para WEB  &copy;<br />
      Desenvolvido pela Secretaria de Educa&ccedil;&atilde;o da Bahia.<br />
      <br />
    </p>
      
      <p>
    <a href="http://validator.w3.org/check?uri=referer" target="_blank"><img
        src="http://www.w3.org/Icons/valid-xhtml10"
        alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
  </p>

    </div>
  </div>
  
  
</div>
</body>

</html>
