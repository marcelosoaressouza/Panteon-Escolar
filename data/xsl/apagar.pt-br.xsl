<?xml version="1.0"?>

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

Desenvolvido dentro do Projeto Contéudos Digitais
Financiado pela Secretária da Educação do Governo do estado da Bahia
em parceria com o Ministério da Educação (MEC), Governo Federal do Brasil.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

http://www.gnu.org/licenses/gpl-2.0.html

</xsl:comment>

<html>


<head>
<title><xsl:value-of select="page/meta/title" /></title>
<link rel="shortcut icon" href="static/images/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="O Panteon Escolar é um sistema para criação, análise e diagnóstico de estudos de Temas Panteon sobre temáticas ligadas à escolas do ensino médio. É uma ferramenta Web de aplicação de modelos de análise genéricos à situações-problema construidas de forma colaborativa e compartilhada no ambiente escolar. " />
<meta name="keywords" content="Educação, Web, Conhecimento, Aprendizado, Aprendizagem, Cognição" />

  <!-- Inicio Snippet XMLNuke - Base -->

  <xmlnuke-htmlheader />
  <xmlnuke-scripts />

  <!-- Fim Snippet XMLNuke - Base -->

  <!-- Inicio CSS e JS Default do Panteon Escolar -->

  <link href="static/js/fancybox/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css"/>
  <link href="static/css/panteonescolar-default.css" rel="stylesheet" type="text/css"/>
  <link href="static/css/principal.css" rel="stylesheet" type="text/css"/>

  <script type="text/javascript" src="static/js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
  <script type="text/javascript" src="static/js/panteonescolar-default.js"></script>
  <script type="text/javascript" src="static/js/jquery-ui/ui.datepicker-pt-BR.js"></script>

  <!-- Fim CSS e JS Default do Panteon Escolar -->

</head>

<body>

<!-- Inicio Layout Principal -->

      <div id="boxBaixoEsquerdaContentApagar">
        <div id="conteudoBoxBaixoHomeApagar">

        <xmlnuke-blockcentercss />
        
        </div>
      </div>
  
<!-- Fim Layout Principal -->

</body>
</html>

</xsl:template>
  <xmlnuke-htmlbody />
</xsl:stylesheet>