<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" encoding="UTF-8"/>
<xsl:output method="xml" omit-xml-declaration="yes"

	doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
	doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"

	indent="yes"/>
<xsl:template match="/">
<xsl:comment>

Sistema Web Educacional Panteon Escolar
Salvador, Bahia, Brasil. 2010.

Equipe:
Yuri Wanderley (yuri.wanderley at gmail dot com) - Coordenação de Desenvolvimento
Marcelo Soares Souza (marcelo at juntadados dot org) - Análise e Codificação
Tarcisio Araujo (tatauphp at gmail dot com) - Codificação e Bug Man.

Desenvolvido dentro do Projeto Contéudos Digitais (A Física e o Cotidiano)
Financiado pela Secretária da Educação do Governo do Estado da Bahia
em parceria com o Ministério da Educação (MEC), Governo Federal do Brasil.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

http://www.gnu.org/licenses/gpl-2.0.html

</xsl:comment>

<html xml:lang="pt-br" lang="pt-br">
<head>
<title><xsl:value-of select="page/meta/title" /></title>
<link rel="shortcut icon" href="static/images/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="O Panteon Escolar é um sistema para criação, análise e diagnóstico de estudos de Temas Panteon sobre temáticas ligadas à escolas do ensino médio. É uma ferramenta Web de aplicação de modelos de análise genéricos à situações-problema construidas de forma colaborativa e compartilhada no ambiente escolar. " />
<meta name="keywords" content="Educação, Web, Conhecimento, Aprendizado, Aprendizagem, Cognição" />
<meta name="google-site-verification" content="dFgxFUUpqzDTb8kYTCeZ6cQdF6uFlrx9yxOp45D01YI" />

  <!-- Inicio Snippet XMLNuke - Base -->
  <xmlnuke-htmlheader />
  <xmlnuke-scripts />

  <!-- Inicio CSS e JS Default do Panteon Escolar -->
  <script type="text/javascript" src="static/js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
  <script type="text/javascript" src="static/js/panteonescolar-default.js"></script>
  <script type="text/javascript" src="static/js/jquery-ui/ui.datepicker-pt-BR.js"></script>
  
  <link href="static/js/fancybox/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css"/>
  <link href="static/css/panteonescolar-default.css" rel="stylesheet" type="text/css"/>
  <link href="static/css/principal.css" rel="stylesheet" type="text/css"/>

  <!-- Fim CSS e JS Default do Panteon Escolar -->

  <!-- Fim Snippet XMLNuke - Base -->

</head>

<body>

<!-- Inicio Layout Principal -->

<div id="baseLayout">
  <xmlnuke-blockright/>

<div id="content">
    <a name="Ponto01" id="Ponto01"></a>
      <!-- Inicio Menu dos Usuarios -->

      <xmlnuke-blockmenu/>

      <!-- Fim Menu dos Usuarios -->

    <div id="boxBaixoEsquerda">

     <!-- Inicio Bloco Central Dados -->

      <!-- Titulo Principal -->
      <div id="boxBaixoEsquerdaTitulo">

        <div id="BoxTituloBoxBaixo">
          <!-- <xsl:value-of select="page/meta/title" /> -->
          <xsl:value-of select="page/meta/abstract" />
        </div>

      </div>

      <!-- Inicio Area Texto Principal -->

      <div id="boxBaixoEsquerdaContent">
        <div id="conteudoBoxBaixoHome">

        <xmlnuke-blockcentercss />

        </div>
      </div>

      <!-- Fim Area Texto Principal -->


     <!-- Fim Bloco Central Dados -->

      <div id="boxBaixoEsquerdaRodape"></div>
    </div>

    <!-- Inicio Box da Direita -->
    <div id="boxDireita">

      <!-- Inicio Barra Vertical -->

      <xmlnuke-blockleftcss />

      <!-- Fim Barra Vertical -->

    </div>
    <!-- Fim - Box da Direita -->

    <div id="rodape">
      <p>
        <img src="static/images/logo/marcaFisica.png" alt="Logo Física"/>
        <img src="static/images/logo/marcauneb.png" alt="Logo UNEB"/>

        <img src="static/images/logo/sec_original.png" alt="Logo SEC"/>
        <!-- <img src="static/images/logo/marca_todospelaescola.png" alt="Logo Todos"/> -->
        <!-- <img src="static/images/logo/marcasec.png" alt="Logo SEC"/> -->
      </p>

      <p>
        <!-- <img src="static/images/logo/min_cienciaetecnologia.png" alt="Logo Min Cien"/> -->
        <!-- <img src="static/images/logo/min_educacao.png" alt="Logo Min Educ"/> -->
        <!-- <img src="static/images/logo/brasil.png" alt="Logo Brasil"/> -->
        <img src="static/images/logo/Logos_Gov_Federal.png" alt="Logo Brasil"/>

      </p>

      <p>
        <a href="http://validator.w3.org/check?uri=referer"> <img src="static/images/logo/valid-xhtml10.png" alt="Valid XHTML 1.0 Transitional" height="31" width="88" /> </a>
        <a href="http://creativecommons.org/licenses/by/2.5/"><img src="static/images/logo/Creative_88x31.png" alt="Logo Creative"/></a>
      </p>

      <p><strong> Sistema Web Educacional Panteon Escolar.</strong></p>
      <p><strong> Este é um <a href="http://pt.wikipedia.org/wiki/Software_livre">Software Livre</a> licenciado sobre a <a href="http://www.gnu.org/licenses/gpl-2.0.html">GPLv2.</a></strong></p>
      <p><strong> Desenvolvido utilizando o Framework <a href="http://www.xmlnuke.com/site/">XMLNuke 3.x</a> (PHP5).</strong></p>

    </div> <!-- div-rodape -->

</div> <!-- div-content -->
</div> <!-- div-baseLayout -->
  
<!-- Fim Layout Principal -->

</body>
</html>

</xsl:template>
  <xmlnuke-htmlbody />
  <xmlnuke-blockhead />
  <xmlnuke-blockmensagem />
  <xmlnuke-blockabausuario />
  <xmlnuke-blockbarramenu />
</xsl:stylesheet>
