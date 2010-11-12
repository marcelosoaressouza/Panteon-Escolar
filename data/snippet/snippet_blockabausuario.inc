<xsl:template match="blockabausuario">
<xsl:apply-templates />
  <xsl:for-each select="imagem_aba">
    <a href="{@href}"> <img src="{@src}" alt="Menu" longdesc="Menu"/> </a>
  </xsl:for-each>
</xsl:template>

