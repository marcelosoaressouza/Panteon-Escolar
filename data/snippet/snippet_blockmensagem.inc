<xsl:template match="blockmensagem">
  <xsl:if test="@criartemapanteon">
    <a href="./criartemapanteon"><img src="static/images/Layout/btnCriarTema.png" width="210" height="45" alt="Criar Tema" longdesc="static/images/Layout/btnCriarTema.png" title="Criar tema" /></a>
  </xsl:if>
  <div id="baseForumHomeTopo"></div>

  <xsl:apply-templates />
  <p></p>
    <div id="boxBaixoDireitaTitulo">
    <xsl:for-each select="box_titulo">
      <div id="BoxTituloDireitaBoxBaixo"><xsl:value-of select="@titulo"/></div>
    </xsl:for-each>
    </div>


  <div id="baseForumHome">
  <div id="ForumHomeContent">

  <xsl:if test="@link_info">
      <center>
        <xsl:value-of select="@link_info"/>
      </center>
      <br/>
  </xsl:if>

  <xsl:for-each select="mensagem">
    <p>
      <xsl:value-of select="@titulo"/>
      <xsl:value-of select="@conteudo" />
    </p>

    <p> - </p>

    <p>
      <xsl:value-of select="@autor" />
    </p>
    
    <p>
      <xsl:value-of select="@link" />
    </p>

    <p class="pcentro">
      <img src="static/images/Separador.png" width="14" height="13" alt="Separador" longdesc="Separador"/>
    </p>
  </xsl:for-each>

  </div>
  </div>
  <div id="baseForumHomeRodape"></div>

</xsl:template>