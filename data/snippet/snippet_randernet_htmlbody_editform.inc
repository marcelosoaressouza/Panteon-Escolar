<!-- USED ONLY WITH SNIPPET-HTMLBODY-BASIC -->
<!-- ATENTION: YOU NEED PUT IN YOUR HEADER SNIPPET_HTMLHEADER.INC -->
<!--- EDITFORM -->


<xsl:template name="randernet.generic.label">
	<!--xsl:param name="caption"/>
	<xsl:param name="name"/>
	<xsl:param name="required"/>

	<label>
		<xsl:if test="$name and $name!=''"><xsl:attribute name="for"><xsl:value-of select="$name"/></xsl:attribute></xsl:if>
		<xsl:value-of select="$caption"/><xsl:if test="$required='true'">*</xsl:if>
	</label -->
        <xsl:param name="caption"/>
	<xsl:param name="name"/>
	<xsl:param name="required"/>
<!-- Aqui Porra 21 maio 2010 - tirando a porra do id -->
        <div id="formGeralLabel{$name}">
	<label>
                <xsl:attribute name="for"><xsl:value-of select="$name"/></xsl:attribute>
		<xsl:if test="$name and $name!=''"></xsl:if>
                <xsl:value-of select="$caption"/>
                <xsl:if test="$caption and $caption!='#'"></xsl:if>
                <xsl:if test="$required='true'">*</xsl:if>
	</label>
        </div>
</xsl:template>

<xsl:template name="randernet.generic.input">
	<xsl:param name="name"/>
	<xsl:param name="type"/>
	<xsl:param name="value"/>
	<xsl:param name="size"/>
	<xsl:param name="maxlength"/>
	<xsl:param name="selected"/>
        <xsl:param name="randernet_ajax"/>


        <!-- Aqui Porra -->
	<xsl:if test="$type='label'">
<!-- Aqui Porra 21 maio 2010 - tirando a porra do id -->

		<span id="form_label">
			<xsl:apply-templates/>
			<!-- <xsl:value-of select="@value"/> -->
                        <xsl:if test="@value=''">a
                            <img src="common/spacer.gif" width="1" height="1" alt="spacer" />
                        </xsl:if>
		</span>
	</xsl:if>


	<!-- xsl:if test="$type='label'">
		<span id="form_label">
			<xsl:apply-templates/>
			<xsl:value-of select="@value"/><xsl:if test="@value=''">&#160;</xsl:if>
		</span>
	</xsl:if -->
	<xsl:if test="$type='select'">
            <xsl:if test="$randernet_ajax='true'">
                <div>
                    <xsl:attribute name="id">div_randernet_select_ajax_<xsl:value-of select="@name"/></xsl:attribute>
                <select>
			<xsl:if test="$size!=''"><xsl:attribute name="size"><xsl:value-of select="@size"/></xsl:attribute></xsl:if>
			<xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
			<xsl:attribute name="id"><xsl:value-of select="@name"/></xsl:attribute><xsl:apply-templates select="option" />
		</select>
                </div>
            </xsl:if>
            <xsl:if test="$randernet_ajax!='true'">
                <select>
			<xsl:if test="$size!=''"><xsl:attribute name="size"><xsl:value-of select="@size"/></xsl:attribute></xsl:if>
			<xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
			<xsl:attribute name="id"><xsl:value-of select="@name"/></xsl:attribute><xsl:apply-templates select="option" />
		</select>
            </xsl:if>
	</xsl:if>
	<xsl:if test="$type!='label' and $type!='select'">
	<input>
		<xsl:attribute name="type">
			<xsl:if test="$type!='radiobox' and $type!='textbox' "><xsl:value-of select="$type"/></xsl:if>
			<xsl:if test="$type='textbox'">text</xsl:if>
			<xsl:if test="$type='radiobox'">radio</xsl:if>
		</xsl:attribute>
		<xsl:if test="$selected='yes'"><xsl:attribute name="checked"/></xsl:if>
		<xsl:attribute name="name"><xsl:value-of select="$name"/></xsl:attribute>
		<xsl:attribute name="id"><xsl:value-of select="$name"/></xsl:attribute>
		<xsl:attribute name="value"><xsl:value-of select="$value"/></xsl:attribute>

		<xsl:if test="$size and $size!=''"><xsl:attribute name="size"><xsl:value-of select="$size"/></xsl:attribute></xsl:if>
		<xsl:if test="$maxlength and $maxlength!=''"><xsl:attribute name="maxlength"><xsl:value-of select="$maxlength"/></xsl:attribute></xsl:if>
	</input>
	</xsl:if>
</xsl:template>

<xsl:template match="caption">
	<dt class="caption" >
		<b><xsl:value-of select="text()"/></b>
	</dt>
</xsl:template>

<!-- randernet.generic Templates -->
<xsl:template match="inputgroup|checkbox|radiobox|textbox|password|file|label|select">

	<!-- PUT CANHIDE OBJECT -->
	<xsl:if test="name()='inputgroup' and @name and @canhide='true'">
		<dt class="group" >
			<a><xsl:attribute name="href">javascript:void showHideGeneric('<xsl:value-of select="@name"/>', 'auto')</xsl:attribute>
			<img border="0" >
				<xsl:attribute name="src">common/imgs/<xsl:if test="@visible='false'">faqopen.gif</xsl:if><xsl:if test="not(@visible='false')">faqclose.gif</xsl:if></xsl:attribute>
				<xsl:attribute name="id">I_<xsl:value-of select="@name"/></xsl:attribute></img>
			</a>
			<xsl:value-of select="@caption" />
		</dt>
	</xsl:if>

	<!-- INPUTGROUP and BREAKLINE -->
	<xsl:if test="name()='inputgroup' and @breakline='true'">
		<xsl:apply-templates />
	</xsl:if>

	<!-- INPUT OBJECTS -->
	<xsl:if test="name()!='inputgroup'">
			<!-- textbox|password|file|label -->
			<dt>
				<xsl:if test="name()='checkbox' or name()='radiobox'">
					&#160;
				</xsl:if>
				
				<xsl:if test="name()!='checkbox' and name()!='radiobox' and name()!='inputgroup'">
					<xsl:if test="local-name(parent::node())='inputgroup' and parent::node()/@name"><xsl:attribute name="id"><xsl:value-of select="parent::node()/@name" /></xsl:attribute><xsl:if test="parent::node()/@visible"><xsl:attribute name="style">display:none</xsl:attribute></xsl:if></xsl:if>
					<xsl:call-template name="randernet.generic.label">
						<xsl:with-param name="caption"><xsl:value-of select="@caption"/></xsl:with-param>
						<xsl:with-param name="name"><xsl:value-of select="@name"/></xsl:with-param>
						<xsl:with-param name="required"><xsl:value-of select="@required"/></xsl:with-param>
					</xsl:call-template>
				</xsl:if>
			</dt>
			<dd>
				
				<xsl:if test="local-name(parent::node())='inputgroup' and parent::node()/@name"><xsl:attribute name="id"><xsl:value-of select="parent::node()/@name" /></xsl:attribute><xsl:if test="parent::node()/@visible"><xsl:attribute name="style">display:none</xsl:attribute></xsl:if></xsl:if>
				<xsl:call-template name="randernet.generic.input">
					<xsl:with-param name="type"><xsl:value-of select="name()"/></xsl:with-param>
					<xsl:with-param name="name"><xsl:value-of select="@name"/></xsl:with-param>
					<xsl:with-param name="value"><xsl:value-of select="@value"/></xsl:with-param>
					<xsl:with-param name="size"><xsl:value-of select="@size"/></xsl:with-param>
					<xsl:with-param name="maxlength"><xsl:value-of select="@maxlength"/></xsl:with-param>
					<xsl:with-param name="selected"><xsl:value-of select="@selected"/></xsl:with-param>
                                        <xsl:with-param name="randernet_ajax"><xsl:value-of select="@randernet_ajax"/></xsl:with-param>
				</xsl:call-template>
				
				<xsl:if test="name()='checkbox' or name()='radiobox'">
					<xsl:call-template name="randernet.generic.label">
						<xsl:with-param name="caption"><xsl:value-of select="@caption"/></xsl:with-param>
						<xsl:with-param name="name"><xsl:value-of select="@name"/></xsl:with-param>
						<xsl:with-param name="required"><xsl:value-of select="@required"/></xsl:with-param>
					</xsl:call-template>
				</xsl:if>
			</dd>
		</xsl:if>

	<!-- Dont breakline!! -->
	<xsl:if test="name()='inputgroup' and (@breakline!='true' or not(@breakline))">
		<dt>
			<xsl:if test="@name"><xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute></xsl:if>
			<xsl:call-template name="randernet.generic.label">
				<xsl:with-param name="caption"><xsl:value-of select="child::*[1]/@caption"/></xsl:with-param>
				<xsl:with-param name="name"><xsl:value-of select="child::*[1]/@name"/></xsl:with-param>
				<xsl:with-param name="required"><xsl:value-of select="child::*[1]/@required"/></xsl:with-param>
			</xsl:call-template>
		</dt>
		<dd>
                   
			<xsl:if test="@name"><xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute></xsl:if>
			<xsl:for-each select="textbox|password|checkbox|radiobox|file|label|select">
				<xsl:if test="position()!=1">
					<xsl:call-template name="randernet.generic.label">
						<xsl:with-param name="caption"><xsl:value-of select="@caption"/></xsl:with-param>
						<xsl:with-param name="name"><xsl:value-of select="@name"/></xsl:with-param>
						<xsl:with-param name="required"><xsl:value-of select="@required"/></xsl:with-param>
					</xsl:call-template>
				</xsl:if>
				<xsl:call-template name="randernet.generic.input">
					<xsl:with-param name="type"><xsl:value-of select="name()"/></xsl:with-param>
					<xsl:with-param name="name"><xsl:value-of select="@name"/></xsl:with-param>
					<xsl:with-param name="value"><xsl:value-of select="@value"/></xsl:with-param>
					<xsl:with-param name="size"><xsl:value-of select="@size"/></xsl:with-param>
					<xsl:with-param name="maxlength"><xsl:value-of select="@maxlength"/></xsl:with-param>
					<xsl:with-param name="selected"><xsl:value-of select="@selected"/></xsl:with-param>
                                        <xsl:with-param name="randernet_ajax"><xsl:value-of select="@randernet_ajax"/></xsl:with-param>
				</xsl:call-template>
				<xsl:if test="position()!=last()"> &#160; </xsl:if>
			</xsl:for-each>
			<xsl:for-each select="hidden">
				<input type="hidden"><xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute><xsl:attribute name="id"><xsl:value-of select="@name"/></xsl:attribute><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute></input>
			</xsl:for-each>
                    
		</dd>
	</xsl:if>
</xsl:template>


<xsl:template match="option">
	<option><xsl:if test="@selected='yes'"><xsl:attribute name="selected"/></xsl:if><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute><xsl:apply-templates /></option>
</xsl:template>
