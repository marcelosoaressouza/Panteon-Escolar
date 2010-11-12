<xsl:template match="blockhead">
<xsl:apply-templates />
<xsl:if test="@perfil"> <link href="static/css/principal-{@perfil}.css" rel="stylesheet" type="text/css"/> </xsl:if>
</xsl:template>