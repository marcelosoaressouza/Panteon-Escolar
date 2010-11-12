<xsl:for-each select="page/blockmenu">
  <div id="MenuBoxUsuario">
    <xsl:apply-templates select="body"/>
  </div>
</xsl:for-each>
