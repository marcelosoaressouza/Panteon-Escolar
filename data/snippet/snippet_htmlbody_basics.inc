<xsl:param name="xml" />
<xsl:param name="xsl" />
<xsl:param name="site" />
<xsl:param name="lang" />
<xsl:param name="transformdate" />
<xsl:param name="urlbase" />
<xsl:param name="engine" />

<xsl:template match="body">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="code">
  <xsl:if test="@information">
    <div style="background-color: white; font-weight: bold">
      <xsl:value-of select="@information" />
    </div>
  </xsl:if>
  <div style="margin: 5px 0; padding: 10px; text-align: left; display: block; overflow: auto; font: 500 1em/1em 'Lucida Console', 'courier new', monospace; background: #FAFAFA; border: 1px solid #f2f2f2; border-left: 3px solid #c0c0c0;">
    <pre>
      <xsl:value-of select="text()"/>
    </pre>
  </div>
</xsl:template>

<xsl:template match="p">
	<p><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="text()"><xsl:value-of select="."/></xsl:template>

<xsl:template match="br"><br/></xsl:template>
<xsl:template match="u"><u><xsl:apply-templates /></u></xsl:template>
<xsl:template match="i"><em><xsl:apply-templates /></em></xsl:template>
<xsl:template match="b"><b><xsl:apply-templates /></b></xsl:template>
<xsl:template match="center"><center><xsl:apply-templates/><xsl:value-of select="text()"/></center></xsl:template>
<xsl:template match="a"><a><xsl:attribute name="href"><xsl:value-of select="@href"/></xsl:attribute><xsl:if test="@target"><xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute></xsl:if><xsl:apply-templates /></a></xsl:template>

<xsl:template match="img"><img><xsl:attribute name="src"><xsl:value-of select="@src"/></xsl:attribute><xsl:if test="@alt"><xsl:attribute name="alt"><xsl:value-of select="@alt"/></xsl:attribute><xsl:attribute name="title"><xsl:value-of select="@alt"/></xsl:attribute></xsl:if><xsl:if test="not(@alt)"><xsl:attribute name="alt">Image</xsl:attribute></xsl:if><xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if><xsl:if test="@height"><xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute></xsl:if><xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if><xsl:if test="@altimage"><xsl:attribute name="onerror">this.onerror=null; this.src='<xsl:value-of select="@altimage"/>'</xsl:attribute></xsl:if></img></xsl:template>

<xsl:template match="ul">
	<ul><xsl:if test="@name"><xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute></xsl:if>
		<xsl:apply-templates select="li" />
	</ul>
</xsl:template>
<xsl:template match="ol">
	<ol><xsl:if test="@name"><xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute></xsl:if>
		<xsl:apply-templates select="li" />
	</ol>
</xsl:template>

<xsl:template match="li"><li><xsl:apply-templates/></li></xsl:template>
<xsl:template match="script">
<script><xsl:attribute name="language"><xsl:value-of select="@language"/></xsl:attribute><xsl:if test="@src"><xsl:attribute name="src"><xsl:value-of select="@src"/></xsl:attribute></xsl:if>
<xsl:value-of select="text()"/>

</script>
</xsl:template>

<xsl:template match="table"><table><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute><xsl:apply-templates select="tr"/></table></xsl:template>

<xsl:template match="tr"><tr><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute><xsl:apply-templates select="td"/></tr></xsl:template>

<xsl:template match="td"><td><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute><xsl:attribute name="style"><xsl:value-of select="@style"/></xsl:attribute><xsl:attribute name="colspan"><xsl:value-of select="@colspan"/></xsl:attribute><xsl:attribute name="rowspan"><xsl:value-of select="@rowspan"/></xsl:attribute><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute><xsl:apply-templates /></td></xsl:template>

<xsl:template match="external">
	<iframe frameborder="0"><xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute><xsl:attribute name="src"><xsl:value-of select="@src"/></xsl:attribute><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute><xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute>&#32;</iframe>
</xsl:template>

<xsl:template match="flash">
	<script language="JavaScript" type="text/javascript">
	if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		var hasRightVersion = DetectFlashVer(<xsl:value-of select="@major"/>, <xsl:value-of select="@minor"/>, <xsl:value-of select="@revision"/>);
		if(hasRightVersion) { 
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=<xsl:value-of select="@major"/>,<xsl:value-of select="@minor"/>,<xsl:value-of select="@revision"/>,0',
				'width', '<xsl:value-of select="@width"/>',
				'height', '<xsl:value-of select="@height"/>',
				'movie', '<xsl:value-of select="@movie"/>',
				'pluginspage', 'http://www.macromedia.com/go/getflashplayer'<xsl:for-each select="param">,
				'<xsl:value-of select="@name" />', '<xsl:value-of select="@value" />'
				</xsl:for-each>
				); 
		} else { 
			var alternateContent = 'This content requires the Adobe Flash Player. '
			+ '<u><a href="http://www.macromedia.com/go/getflash/">Get Flash</a></u>.';
			document.write(alternateContent); 
		}
	}
	</script>
	<noscript>
		<p>This content requires JavaScript.</p>
	</noscript>
</xsl:template>


<xsl:template match="container">
	<div><xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align" /></xsl:attribute></xsl:if><xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class" /></xsl:attribute></xsl:if><xsl:if test="@style"><xsl:attribute name="style"><xsl:value-of select="@style" /></xsl:attribute></xsl:if><xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id" /></xsl:attribute></xsl:if><xsl:apply-templates /></div>
	<xsl:if test="@timeout">
	<script language="javascript">
	setTimeout("hide<xsl:value-of select="@id" />()", <xsl:value-of select="@timeout" />);
	function hide<xsl:value-of select="@id" />() {
		document.getElementById("<xsl:value-of select="@id" />").style.display = "none";
	}
	</script>
	</xsl:if>
</xsl:template>

<xsl:template match="container_ajax">
	<div><xsl:if test="@id"><xsl:attribute name="id">container_ajax_<xsl:value-of select="@id" /></xsl:attribute></xsl:if><xsl:apply-templates /></div>
	       
        <xsl:if test="@idtargetobject">
            <script type="text/javascript" lang="javascript">
            $(document).ready(function() {
                	$("#<xsl:value-of select="@id" />").change(function() {
                        
                            var auxUrl = '<xsl:value-of select="@updateurl" />';
                            var arrAuxUrl = auxUrl.split("/");
                            var auxUrl = arrAuxUrl[arrAuxUrl.length-1];
                            var selected_value = this.value;
                            var varURL = auxUrl+"&amp;ajaxvalueid="+selected_value;

                            $('#<xsl:value-of select="@idtargetobject" />').val('');
                            $('#<xsl:value-of select="@idtargetobject" />').change();
                            
                            $.ajax({
                                type: "GET",
                                url: varURL,
                                success: function(msg){
                                    msg = msg.replace("<dados>","").replace("</dados>","");
                                    $('#container_ajax_<xsl:value-of select="@idtargetobject" />').html(msg);
                                }
                            });
                        });
            });
        </script>
	</xsl:if>
</xsl:template>