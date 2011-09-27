<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml"
		omit-xml-declaration="yes"
		indent="yes"/>
    <xsl:template match="/">
        <dados>
            <xmlnuke-blockcenterajax />
        </dados>      
    </xsl:template>
    <xmlnuke-htmlbody />
</xsl:stylesheet>
