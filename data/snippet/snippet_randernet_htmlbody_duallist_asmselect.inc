<xsl:template match="randernet_duallist_asmselect">
                 <dt>
	         <label><xsl:value-of select="leftlist/@caption"/></label> </dt>
                 <dd>
	         <select>
	         <xsl:attribute name="name">randernet_asmselect_<xsl:value-of select="leftlist/@name"/></xsl:attribute>
	         <xsl:attribute name="id">randernet_asmselect_<xsl:value-of select="leftlist/@name"/></xsl:attribute>
                 <xsl:attribute name="title"><xsl:value-of select="leftlist/@title"/></xsl:attribute>
                 <xsl:attribute name="multiple">multiple</xsl:attribute>
	         	<xsl:for-each select="leftlist/item">
                            <option>
                                    <xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
                                    <xsl:if test="@selected">
                                        <xsl:attribute name="selected"><xsl:value-of select="@selected"/></xsl:attribute>
                                    </xsl:if>
                                    <xsl:value-of select="@text"/>
                            </option>
                        </xsl:for-each>
	         </select>
                 <input type="hidden">
                    <xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
                    <xsl:attribute name="id"><xsl:value-of select="@name"/></xsl:attribute>
                    <xsl:attribute name="value">
                        <xsl:for-each select="leftlist/item">
                            <xsl:if test="@selected">
                                <xsl:value-of select="@id"/><xsl:if test="position()!=last()">,</xsl:if>
                            </xsl:if>
                        </xsl:for-each>
                    </xsl:attribute>
                 </input>
                 </dd>
	<xsl:value-of select="."/>
</xsl:template>
