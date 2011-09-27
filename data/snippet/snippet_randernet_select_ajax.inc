
<xsl:template match="randernet_ajax_select">
    <select>
        <xsl:if test="@size!=''"><xsl:attribute name="size"><xsl:value-of select="@size"/></xsl:attribute></xsl:if>
        <xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
        <xsl:attribute name="id"><xsl:value-of select="@name"/></xsl:attribute><xsl:apply-templates select="option" />
    </select>
</xsl:template>
